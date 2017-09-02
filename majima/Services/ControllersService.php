<?php
/**
 * Copyright (c) 2017
 *
 * @package   Majima
 * @author    David Neustadt <kontakt@davidneustadt.de>
 * @copyright 2017 David Neustadt
 * @license   MIT
 */

namespace Majima\Services;

use Majima\Controller\AdminController;
use Majima\Controller\IndexController;
use Majima\Controller\InstallController;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ControllersService
 * @package Majima\Services
 */
class ControllersService implements ControllersServiceInterface
{
    /**
     * @return array
     */
    public function getControllers()
    {
        return [
            'majima.admin_controller' => AdminController::class,
            'majima.index_controller' => IndexController::class,
            'majima.install_controller' => InstallController::class,
        ];
    }

    /**
     * @param ContainerBuilder $container
     */
    public function registerControllers(ContainerBuilder $container)
    {
        foreach ($this->getControllers() as $id => $class) {
            if (!$container->has($id)) {
                $container->register($id, $class)
                    ->addArgument(new Reference('service_container'))
                    ->addArgument(new Reference('router'));
            }
        }
    }
}