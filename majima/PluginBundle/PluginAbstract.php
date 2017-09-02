<?php
/**
 * Copyright (c) 2017
 *
 * @package   Majima
 * @author    David Neustadt <kontakt@davidneustadt.de>
 * @copyright 2017 David Neustadt
 * @license   MIT
 */

namespace Majima\PluginBundle;

use Majima\PluginBundle\Components\AssetCollection;
use Majima\PluginBundle\Components\ControllerCollection;
use Majima\PluginBundle\Components\RouteCollection;
use Majima\PluginBundle\Components\ViewCollection;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Interface PluginAbstract
 * @package Majima\PluginBundle
 */
abstract class PluginAbstract implements PluginInterface
{
    /**
     * @var ContainerBuilder
     */
    protected $container;

    /**
     * @var string
     */
    private $version = '1.0.0';

    /**
     * @var int
     */
    private $priority = 1;

    /**
     * PluginAbstract constructor.
     * @param ContainerBuilder $container
     */
    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    public function install()
    {

    }

    /**
     * @param $version
     */
    public function update($version)
    {

    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    public function build()
    {

    }

    /**
     * @return ControllerCollection
     */
    public function registerControllers()
    {
        return new ControllerCollection([]);
    }

    /**
     * @return RouteCollection
     */
    public function setRoutes()
    {
        return new RouteCollection();
    }

    /**
     * @return ViewCollection
     */
    public function setViewResources()
    {
        return new ViewCollection();
    }

    /**
     * @return AssetCollection
     */
    public function setCssResources()
    {
        return new AssetCollection();
    }

    /**
     * @return AssetCollection
     */
    public function setJsResources()
    {
        return new AssetCollection();
    }
}