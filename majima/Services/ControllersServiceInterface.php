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

use Symfony\Component\DependencyInjection\ContainerBuilder;

interface ControllersServiceInterface
{
    /**
     * @return array
     */
    public function getControllers();

    /**
     * @param ContainerBuilder $container
     */
    public function registerControllers(ContainerBuilder $container);
}