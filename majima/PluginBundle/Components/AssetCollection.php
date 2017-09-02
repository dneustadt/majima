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
 * Class AssetCollection
 * @package Majima\PluginBundle\Components
 */
class AssetCollection
{
    /**
     * @var string|null
     */
    private $pluginPath;

    /**
     * @var array
     */
    private $backendAssets = [];

    /**
     * @var array
     */
    private $frontendAssets = [];

    /**
     * AssetCollection constructor.
     * @param $pluginPath
     */
    public function __construct($pluginPath = null)
    {
        $this->pluginPath = $pluginPath;
    }

    /**
     * @return array
     */
    public function getBackendAssets()
    {
        return $this->backendAssets;
    }

    /**
     * @param array $backendAssets
     */
    public function setBackendAssets($backendAssets)
    {
        foreach ($backendAssets as &$backendAsset) {
            $backendAsset = $this->pluginPath . DIRECTORY_SEPARATOR . $backendAsset;
        }

        $this->backendAssets = array_merge(
            $this->getBackendAssets(),
            $backendAssets
        );
    }

    /**
     * @return array
     */
    public function getFrontendAssets()
    {
        return $this->frontendAssets;
    }

    /**
     * @param array $frontendAssets
     */
    public function setFrontendAssets($frontendAssets)
    {
        foreach ($frontendAssets as &$frontendAsset) {
            $frontendAsset = $this->pluginPath . DIRECTORY_SEPARATOR . $frontendAsset;
        }

        $this->frontendAssets = array_merge(
            $this->getFrontendAssets(),
            $frontendAssets
        );
    }
}