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
 * Interface ConfigServiceInterface
 * @package Majima\Services
 */
interface ConfigServiceInterface
{
    /**
     * @return array
     */
    public function getArray();
}