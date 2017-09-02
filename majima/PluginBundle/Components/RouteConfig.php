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
 * Class RouteConfig
 * @package Majima\PluginBundle\Components
 */
class RouteConfig
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $action;

    /**
     * @var array
     */
    private $params = [];

    /**
     * RouteConfig constructor.
     * @param $name
     * @param $slug
     * @param $action
     * @param array $params
     */
    public function __construct(
        $name,
        $slug,
        $action,
        $params = []
    )
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->action = $action;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
}