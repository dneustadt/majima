<?php
/**
 * Copyright (c) 2017
 *
 * @package   Majima
 * @author    David Neustadt <kontakt@davidneustadt.de>
 * @copyright 2017 David Neustadt
 * @license   MIT
 */

namespace Majima;

use Leafo\ScssPhp\Compiler;
use Majima\Services\DwooEngineFactory;
use Majima\Services\FluentPdoFactory;
use Patchwork\JSqueeze;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class MajimaController
 * @package Majima
 */
abstract class MajimaController
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var DwooEngineFactory
     */
    protected $engine;

    /**
     * @var FluentPdoFactory
     */
    protected $dbal;

    /**
     * MajimaController constructor.
     * @param Container $container
     * @param RouterInterface $router
     */
    public function __construct(Container $container, RouterInterface $router)
    {
        $this->container = $container;
        $this->router = $router;
        $this->engine = $this->container->get('dwoo.engine');
        $this->dbal = $this->container->get('dbal');

        if (!$this->container->get('session')->isStarted()) {
            $this->container->get('session')->start();
        }

        $this->compileAssets();
    }

    /**
     * @param array $data
     */
    public function assign($data = [])
    {
        $this->engine->setData($data);
    }

    protected function compileAssets()
    {
        $cssPath = BASE_DIR . join(DIRECTORY_SEPARATOR, ['web', 'css', 'style.min.css']);
        $jsPath = BASE_DIR . join(DIRECTORY_SEPARATOR, ['web', 'js', 'scripts.min.js']);
        $pluginCssFiles = $this->container->getParameter('plugins.css.files');
        $pluginJsFiles = $this->container->getParameter('plugins.js.files');

        if (!file_exists($cssPath)) {
            foreach ($pluginCssFiles as $viewport => $viewportCssFiles) {
                /** @var Compiler $compiler */
                $compiler = $this->container->get('scssphp.compiler');

                $importPaths = [];
                $scss = '';

                foreach ($viewportCssFiles as $viewportCssFile) {
                    if (file_exists($viewportCssFile)) {
                        $scss .= file_get_contents($viewportCssFile);
                        $importPaths[] = dirname($viewportCssFile);
                    }
                }

                $compiler->setImportPaths($importPaths);
                $compiler->setFormatter('Leafo\ScssPhp\Formatter\Compressed');
                $css = $compiler->compile($scss);

                if ($viewport == 'backend') {
                    $cssPath = str_replace('min.css', 'backend.min.css', $cssPath);
                }

                file_put_contents($cssPath, $css);
            }
        }

        if (!file_exists($jsPath)) {
            foreach ($pluginJsFiles as $viewport => $viewportJsFiles) {
                /** @var JSqueeze $compiler */
                $compiler = $this->container->get('jsqueeze.compiler');
                $rawJs = '';

                foreach ($viewportJsFiles as $viewportJsFile) {
                    if (file_exists($viewportJsFile)) {
                        $rawJs .= file_get_contents($viewportJsFile);
                    }
                }

                $minifiedJs = $compiler->squeeze(
                    $rawJs,
                    true,   // $singleLine
                    true,   // $keepImportantComments
                    true    // $specialVarRx
                );

                if ($viewport == 'backend') {
                    $jsPath = str_replace('min.js', 'backend.min.js', $jsPath);
                }

                file_put_contents($jsPath, $minifiedJs);
            }
        }
    }
}