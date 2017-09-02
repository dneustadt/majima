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

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\PhpArrayAdapter;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class ConfigService
 * @package Majima\Services
 */
class ConfigService implements ConfigServiceInterface
{
    /**
     * @var Container
     */
    private $container;

    /**
     * ConfigService constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function getArray()
    {
        $securityContext = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');
        $config = [
            'admin' => $securityContext->isGranted('IS_AUTHENTICATED_FULLY'),
            'base_url' => $router->getContext()->getBaseUrl(),
            'path_info' => $router->getContext()->getPathInfo()
        ];

        $cache = new PhpArrayAdapter(
            $this->container->getParameter('kernel.cache_dir') . '/majima.cache',
            new FilesystemAdapter('', 31536000, $this->container->getParameter('kernel.cache_dir'))
        );
        if ($cache->hasItem('majima.cache_buster')) {
            $cacheBuster = $cache->getItem('majima.cache_buster')->get();
        } else {
            $cacheBuster = time();
            $cache->warmUp(['majima.cache_buster' => $cacheBuster]);
        }
        $config['cache_buster'] = $cacheBuster;

        return $config;
    }
}