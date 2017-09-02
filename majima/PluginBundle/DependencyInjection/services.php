<?php
/**
 * Copyright (c) 2017
 *
 * @package   Majima
 * @author    David Neustadt <kontakt@davidneustadt.de>
 * @copyright 2017 David Neustadt
 * @license   MIT
 */

use Symfony\Component\DependencyInjection\Reference;
use Majima\PluginBundle\Services\DwooEngineDecorator;
use Majima\PluginBundle\Services\RoutesServiceDecorator;
use Majima\PluginBundle\Services\PluginService;

$container->register('majima.plugins', PluginService::class)
    ->addArgument(new Reference('dbal'));

$container->register('majima.routes.decorator', RoutesServiceDecorator::class)
    ->setDecoratedService('majima.routes')
    ->addArgument(new Reference('majima.routes.decorator.inner'))
    ->addArgument(new Reference('service_container'))
    ->addArgument(new Reference('dbal'))
    ->setPublic(true);

$container->register('dwoo.engine.decorator', DwooEngineDecorator::class)
    ->setDecoratedService('dwoo.engine')
    ->addArgument(new Reference('dwoo.engine.decorator.inner'))
    ->addArgument(new Reference('service_container'))
    ->addArgument(new Reference('majima.config'))
    ->setPublic(true);