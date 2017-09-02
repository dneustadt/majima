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

/**
 * Interface RoutesServiceInterface
 * @package Majima\Services
 */
interface RoutesServiceInterface
{
    /**
     * @return array
     */
    public function getRoutes();
}