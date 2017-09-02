<?php
/**
 * Copyright (c) 2017
 *
 * @package   Majima
 * @author    David Neustadt <kontakt@davidneustadt.de>
 * @copyright 2017 David Neustadt
 * @license   MIT
 */

namespace Majima\PluginBundle\Services;

use Majima\Services\ConfigService;
use Majima\Services\DwooEngineFactory;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class DwooEngineDecorator
 * @package Majima\PluginBundle\Services
 */
class DwooEngineDecorator extends DwooEngineFactory
{
    /**
     * DwooEngineDecorator constructor.
     * @param DwooEngineFactory $engine
     * @param Container $container
     * @param ConfigService $config
     */
    public function __construct(
        DwooEngineFactory $engine,
        Container $container,
        ConfigService $config
    )
    {
        parent::__construct($container, $config);

        $pluginResourcesDirs = $container->getParameter('plugins.view.dirs');

        foreach($pluginResourcesDirs as $pluginResourcesDir) {
            $viewsDir = $pluginResourcesDir;
            if (!is_dir($viewsDir)) {
                continue;
            }
            $this->setTemplateDir($viewsDir);
        }
    }
}