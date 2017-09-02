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

use Majima\Services\FluentPdoFactory;
use Majima\Services\RoutesService;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class RoutesServiceDecorator
 * @package Plugins\MajimaGrid\Services
 */
class RoutesServiceDecorator extends RoutesService
{
    /**
     * @var RoutesService
     */
    private $service;

    /**
     * @var array
     */
    private $pluginRoutes = [];

    /**
     * RoutesServiceDecorator constructor.
     * @param RoutesService $service
     * @param Container $container
     * @param FluentPdoFactory $fluentPdo
     */
    public function __construct($service, Container $container, FluentPdoFactory $fluentPdo)
    {
        $this->service = $service;
        $this->pluginRoutes = $container->hasParameter('majima.plugin.routes') ? $container->getParameter('majima.plugin.routes') : [];

        parent::__construct($container, $fluentPdo);
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        $routes = $this->service->getRoutes();

        foreach ($this->pluginRoutes as $route) {
            array_push($routes, $route);
        }

        return $routes;
    }
}