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
 * Class ViewCollection
 * @package Majima\PluginBundle\Components
 */
class ViewCollection
{
    /**
     * @var string|null
     */
    private $pluginPath;

    /**
     * @var array
     */
    private $views = [];

    /**
     * ViewCollection constructor.
     * @param $pluginPath
     */
    public function __construct($pluginPath = null)
    {
        $this->pluginPath = $pluginPath;
    }

    /**
     * @return array
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * @param array $views
     */
    public function setViews($views)
    {
        foreach ($views as &$view) {
            $view = $this->pluginPath . DIRECTORY_SEPARATOR . $view;
        }

        $this->views = array_merge(
            $this->getViews(),
            $views
        );
    }
}