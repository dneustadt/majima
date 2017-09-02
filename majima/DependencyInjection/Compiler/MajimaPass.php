<?php
/**
 * Copyright (c) 2017
 *
 * @package   Majima
 * @author    David Neustadt <kontakt@davidneustadt.de>
 * @copyright 2017 David Neustadt
 * @license   MIT
 */

namespace Majima\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MajimaPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        /*
         * Register Controllers from controllers service
         */
        $container->get('majima.controllers')->registerControllers($container);

        $configPath = BASE_DIR . join(DIRECTORY_SEPARATOR, ['var', 'conf', 'config.json']);

        if (!file_exists($configPath)) {
            return;
        }

        $config = json_decode(file_get_contents($configPath));

        foreach ($config as $key => $value) {
            $container->setParameter($key, $value);
        }
        $container->setParameter('db_config_loaded', true);
    }
}