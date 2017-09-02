<?php
/**
 * Copyright (c) 2017
 *
 * @package   Majima
 * @author    David Neustadt <kontakt@davidneustadt.de>
 * @copyright 2017 David Neustadt
 * @license   MIT
 */

namespace Majima;

use Majima\PluginBundle\PluginBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Class MajimaKernel
 * @package Majima
 */
class MajimaKernel extends Kernel
{
    /**
     * @return array
     */
    public function registerBundles()
    {
        $bundles = [
            new FrameworkBundle(),
            new SecurityBundle(),
            new MajimaBundle(),
            new PluginBundle(),
        ];
        return $bundles;
    }

    /**
     * @param LoaderInterface $loader
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(sprintf('%s/../config/config.php', __DIR__, $this->getEnvironment()));
        $loader->load(sprintf('%s/../config/security.php', __DIR__));
    }

    /**
     * @return string
     */
    public function getLogDir()
    {
        return __DIR__.'/../var/logs';
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return __DIR__.'/../var/cache/'.$this->getEnvironment();
    }
}