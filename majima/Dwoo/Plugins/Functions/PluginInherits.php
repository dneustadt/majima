<?php
/**
 * Copyright (c) 2013-2017
 *
 * @category  Library
 * @package   Dwoo\Plugins\Functions
 * @author    Jordi Boggiano <j.boggiano@seld.be>
 * @author    David Sanchez <david38sanchez@gmail.com>
 * @copyright 2008-2013 Jordi Boggiano
 * @copyright 2013-2017 David Sanchez
 * @license   http://dwoo.org/LICENSE LGPLv3
 * @version   1.3.6
 * @date      2017-03-21
 * @link      http://dwoo.org/
 */

namespace Dwoo\Plugins\Functions;

use Dwoo\Compiler;
use Dwoo\ICompilable;
use Dwoo\Plugin;
use Dwoo\Exception as Exception;
use Dwoo\Security\Exception as SecurityException;
use Dwoo\Compilation\Exception as CompilationException;
use Dwoo\Template\File;

/**
 * Extends another template, read more about template inheritance at {@link
 * http://wiki.dwoo.org/index.php/TemplateInheritance}
 * <pre>
 *  * file : the template to extend
 * </pre>
 * This software is provided 'as-is', without any express or implied warranty.
 * In no event will the authors be held liable for any damages arising from the use of this software.
 */
class PluginInherits extends PluginExtends implements ICompilable
{
    /**
     * @param Compiler $compiler
     * @param          $file
     *
     * @throws CompilationException
     */
    public static function compile(Compiler $compiler, $file)
    {
        list($l, $r) = $compiler->getDelimiters();
        self::$l     = preg_quote($l, '/');
        self::$r     = preg_quote($r, '/');
        self::$regex = '/
			' . self::$l . 'block\s(["\']?)(.+?)\1' . self::$r . '(?:\r?\n?)
			((?:
				(?R)
				|
				[^' . self::$l . ']*
				(?:
					(?! ' . self::$l . '\/?block\b )
					' . self::$l . '
					[^' . self::$l . ']*+
				)*
			)*)
			' . self::$l . '\/block' . self::$r . '
			/six';

        if ($compiler->getLooseOpeningHandling()) {
            self::$l .= '\s*';
            self::$r = '\s*' . self::$r;
        }
        $inheritanceTree = array(array('source' => $compiler->getTemplateSource()));
        $curPath         = dirname($compiler->getCore()->getTemplate()->getResourceIdentifier()) . DIRECTORY_SEPARATOR;
        $curTpl          = $compiler->getCore()->getTemplate();

        while (!empty($file)) {
            if ($file === '""' || $file === "''" || (substr($file, 0, 1) !== '"' && substr($file, 0, 1) !== '\'')) {
                throw new CompilationException($compiler, 'Inherits : The file name must be a non-empty string');
            }

            if (preg_match('#^["\']([a-z]{2,}):(.*?)["\']$#i', $file, $m)) {
                // resource:identifier given, extract them
                $resource   = $m[1];
                $identifier = $m[2];
            } else {
                // get the current template's resource
                $resource   = $curTpl->getResourceName();
                $identifier = substr($file, 1, - 1);
            }

            try {
                $templateDirs = $compiler->getCore()->getTemplateDir();
                $curTemplateDir = str_replace($identifier, '', $curTpl->getResourceIdentifier());
                $curTemplateKey = array_search($curTemplateDir, $templateDirs);
                if ($curTemplateKey !== false && isset($templateDirs[$curTemplateKey+1])) {
                    $nextTemplateDir = $templateDirs[$curTemplateKey+1];
                    $nextTpl = $nextTemplateDir . $identifier;
                    foreach ($templateDirs as $key => $dir) {
                        if ($key <= $curTemplateKey) {
                            unset($templateDirs[$key]);
                        }
                    }
                    $curTpl = new File(
                        $identifier,
                        null,
                        null,
                        null,
                        $templateDirs
                    );
                }
                $parent = $compiler->getCore()->templateFactory($resource, $identifier, null, null, null, $curTpl);
            }
            catch (SecurityException $e) {
                throw new CompilationException($compiler, 'Inherits : Security restriction : ' . $e->getMessage());
            }
            catch (Exception $e) {
                throw new CompilationException($compiler, 'Inherits : ' . $e->getMessage());
            }

            if ($parent === null) {
                throw new CompilationException($compiler, 'Inherits : Resource "' . $resource . ':' . $identifier . '" not found.');
            } elseif ($parent === false) {
                throw new CompilationException($compiler, 'Inherits : Resource "' . $resource . '" does not support inherits.');
            }

            $curTpl    = $parent;
            $newParent = array(
                'source'     => $parent->getSource(),
                'resource'   => $resource,
                'identifier' => $parent->getResourceIdentifier(),
                'uid'        => $parent->getUid()
            );
            if (array_search($newParent, $inheritanceTree, true) !== false) {
                throw new CompilationException($compiler, 'Inherits : Recursive template inheritance detected');
            }
            $inheritanceTree[] = $newParent;

            if (preg_match('/^' . self::$l . 'inherits(?:\(?\s*|\s+)(?:file=)?\s*((["\']).+?\2|\S+?)\s*\)?\s*?' . self::$r . '/i', $parent->getSource(), $match)) {
                $curPath = dirname($identifier) . DIRECTORY_SEPARATOR;
                if (isset($match[2]) && $match[2] == '"') {
                    $file = '"' . str_replace('"', '\\"', substr($match[1], 1, - 1)) . '"';
                } elseif (isset($match[2]) && $match[2] == "'") {
                    $file = '"' . substr($match[1], 1, - 1) . '"';
                } else {
                    $file = '"' . $match[1] . '"';
                }
            } else {
                $file = false;
            }
        }

        while (true) {
            $parent                = array_pop($inheritanceTree);
            $child                 = end($inheritanceTree);
            self::$childSource     = $child['source'];
            self::$lastReplacement = count($inheritanceTree) === 1;
            if (!isset($newSource)) {
                $newSource = $parent['source'];
            }
            $newSource = preg_replace_callback(self::$regex, array(
                'Dwoo\Plugins\Functions\PluginInherits',
                'replaceBlock'
            ), $newSource);

            if (self::$lastReplacement) {
                break;
            }
        }
        $compiler->setTemplateSource($newSource);
        $compiler->recompile();
    }

    /**
     * @param array $matches
     *
     * @return mixed|string
     */
    protected static function replaceBlock(array $matches)
    {
        $matches[3] = self::removeTrailingNewline($matches[3]);

        if (preg_match_all(self::$regex, self::$childSource, $override) && in_array($matches[2], $override[2])) {
            $key      = array_search($matches[2], $override[2]);
            $override = self::removeTrailingNewline($override[3][$key]);

            $l = stripslashes(self::$l);
            $r = stripslashes(self::$r);

            if (self::$lastReplacement) {
                return preg_replace('/' . self::$l . '\$dwoo\.parent' . self::$r . '/is', $matches[3], $override);
            }

            return $l . 'block ' . $matches[1] . $matches[2] . $matches[1] . $r . preg_replace('/' . self::$l . '\$dwoo\.parent' . self::$r . '/is', $matches[3], $override) . $l . '/block' . $r;
        }

        if (preg_match(self::$regex, $matches[3])) {
            return preg_replace_callback(self::$regex, array(
                'Dwoo\Plugins\Functions\PluginInherits',
                'replaceBlock'
            ), $matches[3]);
        }

        if (self::$lastReplacement) {
            return $matches[3];
        }

        return $matches[0];
    }
}
