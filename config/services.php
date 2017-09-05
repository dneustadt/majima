<?php
/**
 * Copyright (c) 2017
 *
 * @package   Majima
 * @author    David Neustadt <kontakt@davidneustadt.de>
 * @copyright 2017 David Neustadt
 * @license   MIT
 */

use Majima\EventListener\BaseControllerListener;
use Majima\Services\ConfigService;
use Majima\Services\ControllersService;
use Majima\Services\DwooEngineFactory;
use Majima\Services\ExceptionHandler;
use Majima\Services\FluentPdoFactory;
use Majima\Services\RoutesService;
use Majima\Services\RoutingLoader;
use Majima\Security\MajimaUserProvider;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Leafo\ScssPhp\Compiler;
use Patchwork\JSqueeze;

$container->setDefinition('majima.controllers', new Definition(
    ControllersService::class,
    []
));

$container->setDefinition('majima.base_controller_subscriber', new Definition(
    BaseControllerListener::class,
    [new Reference('security.token_storage'), new Reference('service_container')]
))->addTag('kernel.event_subscriber');

$container->setDefinition('majima.config', new Definition(
    ConfigService::class,
    [new Reference('service_container')]
));

$container->setDefinition('dwoo.engine', new Definition(
    DwooEngineFactory::class,
    [new Reference('service_container'), new Reference('majima.config')]
));

$container->setDefinition('dbal', new Definition(
    FluentPdoFactory::class,
    [new Reference('service_container')]
));

$container->setDefinition('majima.routes', new Definition(
    RoutesService::class,
    [new Reference('service_container'), new Reference('dbal')]
));

$container->setDefinition('majima.routing_loader', new Definition(
    RoutingLoader::class,
    [new Reference('service_container'), new Reference('majima.routes')]
))->addTag('routing.loader');

$container->setDefinition('majima.admin_provider', new Definition(
    MajimaUserProvider::class,
    [new Reference('service_container'), new Reference('dbal')]
));

$container->setDefinition('majima.exception_handler', new Definition(
    ExceptionHandler::class,
    [new Reference('service_container')]
))->addTag('kernel.event_listener', ['event' => 'kernel.exception', 'method' => 'onKernelException']);

$container->setDefinition('scssphp.compiler', new Definition(
    Compiler::class,
    []
));

$container->setDefinition('jsqueeze.compiler', new Definition(
    JSqueeze::class,
    []
));