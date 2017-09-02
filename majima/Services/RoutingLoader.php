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

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class RoutingLoader
 * @package Majima\Services
 */
class RoutingLoader implements LoaderInterface
{
    /**
     * @var bool
     */
    private $loaded = false;

    /**
     * @var Container
     */
    private $container;

    /**
     * @var RoutesService
     */
    private $routesService;

    public function __construct(Container $container, RoutesService $routesService)
    {
        $this->container = $container;
        $this->routesService = $routesService;
    }

    /**
     * @param mixed $resource
     * @param string|null $type
     * @return RouteCollection
     */
    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "extra" loader twice');
        }

        $collection = new RouteCollection();

        $routes = $this->container->get('majima.routes')->getRoutes();

        foreach ($routes as $route) {
            $collection->add(
                $route['name'],
                new Route(
                    $route['slug'],
                    $route['defaults'],
                    $route['params']
                )
            );
        }

        return $collection;
    }

    /**
     * @param mixed $resource
     * @param string|null $type
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        return 'majima' === $type;
    }

    public function getResolver()
    {
        // needed, but can be blank, unless you want to load other resources
        // and if you do, using the Loader base class is easier (see below)
    }

    /**
     * @param LoaderResolverInterface $resolver
     */
    public function setResolver(LoaderResolverInterface $resolver)
    {
        // same as above
    }
}