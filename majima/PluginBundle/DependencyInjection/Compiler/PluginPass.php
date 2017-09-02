<?php
/**
 * Copyright (c) 2017
 *
 * @package   Majima
 * @author    David Neustadt <kontakt@davidneustadt.de>
 * @copyright 2017 David Neustadt
 * @license   MIT
 */

namespace Majima\PluginBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PluginPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        /*
         * Register Plugins
         */
        if ($container->hasParameter('db_config_loaded')) {
            $container->get('majima.plugins')->registerPlugins($container);
            $container->setParameter('plugins.view.dirs', $container->get('majima.plugins')->getViewResources());
            $container->setParameter('plugins.css.files', $container->get('majima.plugins')->getCssResources());
            $container->setParameter('plugins.js.files', $container->get('majima.plugins')->getJsResources());
        } else {
            $container->setParameter('majima.plugin.routes', []);
        }
    }
}