<?php
/**
 * Copyright (c) 2017
 *
 * @package   Majima
 * @author    David Neustadt <kontakt@davidneustadt.de>
 * @copyright 2017 David Neustadt
 * @license   MIT
 */

namespace Dwoo\Plugins\Functions;

use Dwoo\Core;
use Dwoo\Plugin;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class PluginLink
 */
class PluginLink extends Plugin
{
    /** @var Core */
    protected $core;

    /**
     * @var Container
     */
    private $container;

    public function __construct(Core $core)
    {
        $this->core = $core;
        $globals = $core->getGlobals();
        $this->container = isset($globals['service_container']) ? $globals['service_container'] : null;
        parent::__construct($core);
    }

    public function process($path, $cacheBuster = null)
    {
        $baseUrl = $this->container->get('router')->getContext()->getBaseUrl();
        $url = $baseUrl . '/' . ltrim($path, '/');
        if ($cacheBuster) {
            $url .= '?' . $cacheBuster;
        }

        return $url;
    }
}