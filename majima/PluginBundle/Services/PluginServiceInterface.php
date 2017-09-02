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
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Interface PluginServiceInterface
 * @package Majima\PluginBundle\Services
 */
interface PluginServiceInterface
{
    /**
     * PluginServiceInterface constructor.
     * @param FluentPdoFactory $dbal
     */
    public function __construct(FluentPdoFactory $dbal);

    /**
     * @return array
     */
    public function getRegisteredPlugins();

    public function instantiatePluginClasses();

    /**
     * @param ContainerBuilder $container
     */
    public function registerPlugins(ContainerBuilder $container);

    public function registerRoutes();

    /**
     * @param $pluginName string
     */
    public function registerControllers($pluginName);

    /**
     * @return array
     */
    public function getViewResources();

    /**
     * @return array
     */
    public function getCssResources();
    /**
     * @return array
     */
    public function getJsResources();
}