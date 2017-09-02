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

use Symfony\Component\DependencyInjection\Container;

/**
 * Class RoutesService
 * @package Majima\Services
 */
class RoutesService implements RoutesServiceInterface
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var FluentPdoFactory
     */
    private $fluentPdo;

    /**
     * RoutesService constructor.
     * @param Container $container
     * @param FluentPdoFactory $fluentPdo
     */
    public function __construct(Container $container, FluentPdoFactory $fluentPdo)
    {
        $this->container = $container;
        $this->fluentPdo = $fluentPdo;
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return [
            [
                'name' => 'index_index',
                'slug' => '/',
                'defaults' => ['_controller' => 'majima.index_controller:indexAction'],
                'params' => []
            ],
            [
                'name' => 'install_index',
                'slug' => '/install/',
                'defaults' => ['_controller' => 'majima.install_controller:indexAction'],
                'params' => []
            ],
            [
                'name' => 'admin_login',
                'slug' => '/admin/login/',
                'defaults' => [],
                'params' => []
            ],
            [
                'name' => 'admin_logout',
                'slug' => '/admin/logout/',
                'defaults' => [],
                'params' => []
            ],
            [
                'name' => 'admin_clearcache',
                'slug' => '/admin/clearcache/',
                'defaults' => ['_controller' => 'majima.admin_controller:clearcacheAction'],
                'params' => []
            ],
        ];
    }
}