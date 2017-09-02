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
 * Interface PluginInterface
 * @package Majima\PluginBundle
 */
interface PluginInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function __construct(ContainerBuilder $container);

    public function install();

    /**
     * @param $version
     */
    public function update($version);

    /**
     * @return string
     */
    public function getVersion();

    /**
     * @return int
     */
    public function getPriority();

    public function build();

    /**
     * @return ControllerCollection
     */
    public function registerControllers();

    /**
     * @return RouteCollection
     */
    public function setRoutes();

    /**
     * @return ViewCollection
     */
    public function setViewResources();

    /**
     * @return AssetCollection
     */
    public function setCssResources();

    /**
     * @return AssetCollection
     */
    public function setJsResources();
}