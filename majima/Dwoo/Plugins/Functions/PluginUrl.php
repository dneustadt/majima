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
 * Class PluginUrl
 */
class PluginUrl extends Plugin
{
    /** @var Core */
    protected $core;

    /**
     * @var Container
     */
    private $container;

    /**
     * PluginUrl constructor.
     * @param Core $core
     */
    public function __construct(Core $core)
    {
        $this->core = $core;
        $globals = $core->getGlobals();
        $this->container = isset($globals['service_container']) ? $globals['service_container'] : null;
        parent::__construct($core);
    }

    /**
     * @param $name
     * @param array $params
     * @return mixed
     */
    public function process($name, $params = [])
    {
        $url = $this->container->get('router')->generate(
            $name,
            $params
        );
        return $url;
    }
}