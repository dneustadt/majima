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
 * Class ControllerCollection
 * @package Majima\PluginBundle\Components
 */
class ControllerCollection
{
    /**
     * @var array
     */
    private $controllers = [];

    /**
     * ControllerCollection constructor.
     * @param array $controllers
     */
    public function __construct($controllers)
    {
        $this->controllers = $controllers;
    }

    /**
     * @return array
     */
    public function getControllers()
    {
        return $this->controllers;
    }

    /**
     * @param array $controllers
     */
    public function setControllers($controllers)
    {
        $this->controllers = array_merge(
            $this->getControllers(),
            $controllers
        );
    }
}