<?php
/**
 * Copyright (c) 2017
 *
 * @package   Majima
 * @author    David Neustadt <kontakt@davidneustadt.de>
 * @copyright 2017 David Neustadt
 * @license   MIT
 */

namespace Majima\PluginBundle\Components;

/**
 * Class RouteCollection
 * @package Majima\PluginBundle\Components
 */
class RouteCollection
{
    /**
     * @var array
     */
    private $routes = [];

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param RouteConfig $route
     */
    public function addRoute(RouteConfig $route)
    {
        $this->routes[] = $route;
    }
}