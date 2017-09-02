<?php
/**
 * Copyright (c) 2017
 *
 * @package   Majima
 * @author    David Neustadt <kontakt@davidneustadt.de>
 * @copyright 2017 David Neustadt
 * @license   MIT
 */

namespace Majima\PluginBundle\Services;

use Majima\PluginBundle\Components\RouteConfig;
use Majima\PluginBundle\PluginAbstract;
use Majima\Services\FluentPdoFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class PluginService
 * @package Majima\PluginBundle\Services
 */
class PluginService implements PluginServiceInterface
{
    /**
     * @var FluentPdoFactory
     */
    private $dbal;

    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var array
     */
    private $pluginClasses = [];

    /**
     * @var PluginAbstract
     */
    private $pluginClass;

    /**
     * @var array
     */
    private $registeredPlugins;

    /**
     * PluginService constructor.
     * @param FluentPdoFactory $dbal
     */
    public function __construct(FluentPdoFactory $dbal)
    {
        $this->dbal = $dbal;

        $this->registeredPlugins = $this->dbal
            ->from('plugins')
            ->fetchAll('name');
    }

    /**
     * @return array
     */
    public function getRegisteredPlugins()
    {
        $dirs = glob(BASE_DIR . 'plugins/*', GLOB_ONLYDIR);

        foreach ($dirs as $dir) {
            $pluginName = basename($dir);
            if (isset($this->registeredPlugins[$pluginName]['active']) && !$this->registeredPlugins[$pluginName]['active']) {
                unset($this->registeredPlugins[$pluginName]);
            } else {
                $this->registeredPlugins[$pluginName]['dir'] = $dir;
            }
        }

        foreach ($this->registeredPlugins as $pluginName => $pluginInfo) {
            if (!array_key_exists('dir', $pluginInfo)) {
                $this->dbal->delete('plugins')
                    ->where('id', $pluginInfo['id'])
                    ->execute();
                unset($this->registeredPlugins[$pluginName]);
            }
        }

        return $this->registeredPlugins;
    }

    public function instantiatePluginClasses()
    {
        foreach ($this->getRegisteredPlugins() as $pluginName => $pluginInfo) {
            $pluginClass = 'Plugins\\' . $pluginName . '\\' . $pluginName;
            if (class_exists($pluginClass)) {
                $this->pluginClasses[] = new $pluginClass($this->container);
            }
        }

        usort($this->pluginClasses, function(PluginAbstract $a, PluginAbstract $b) {
            return $a->getPriority() - $b->getPriority();
        });
    }

    /**
     * @param ContainerBuilder $container
     */
    public function registerPlugins(ContainerBuilder $container)
    {
        $this->container = $container;
        $this->instantiatePluginClasses();
        foreach ($this->pluginClasses as $pluginClass) {
            $this->pluginClass = $pluginClass;
            $pluginNamespace = explode('\\', get_class($pluginClass));
            $pluginName = array_pop($pluginNamespace);
            if (!isset($this->registeredPlugins[$pluginName]['id'])) {
                $pluginClass->install();
                $this->dbal->insertInto('plugins', [
                    'name' => $pluginName,
                    'version' => $pluginClass->getVersion(),
                    'active' => 1,
                ])->execute();
            } else if (version_compare($pluginClass->getVersion(), $this->registeredPlugins[$pluginName]['version'], '>')) {
                $pluginClass->update($this->registeredPlugins[$pluginName]['version']);
                $this->dbal->update('plugins')
                    ->set([
                        'version' => $pluginClass->getVersion()
                    ])
                    ->where('id', $this->registeredPlugins[$pluginName]['id'])
                    ->execute();
            }
            $pluginClass->build();
            $this->registerRoutes();
            $this->registerControllers($pluginName);
        }
    }

    public function registerRoutes()
    {
        $routes = $this->container->hasParameter('majima.plugin.routes') ? $this->container->getParameter('majima.plugin.routes') : [];
        /**
         * @var RouteConfig $route
         */
        foreach ($this->pluginClass->setRoutes()->getRoutes() as $route) {
            array_push($routes, [
                'name' => $route->getName(),
                'slug' => $route->getSlug(),
                'defaults' => ['_controller' => $route->getAction()],
                'params' => $route->getParams(),
            ]);
        }
        $this->container->setParameter('majima.plugin.routes', $routes);
    }

    /**
     * @param $pluginName string
     */
    public function registerControllers($pluginName)
    {
        $pluginId = $this->classNameToUnderscore($pluginName);
        foreach ($this->pluginClass->registerControllers()->getControllers() as $id => $class) {
            $serviceId = $pluginId . '.' . $id;
            if ($this->container->has($id)) {
                $service = $this->container->register($serviceId, $class)
                    ->setDecoratedService($id)
                    ->addArgument(new Reference($serviceId . '.inner'))
                    ->setPublic(true);
            } else {
                $service = $this->container->register($serviceId, $class);
            }
            $service->addArgument(new Reference('service_container'))
                ->addArgument(new Reference('router'));
        }
    }

    /**
     * @return array
     */
    public function getViewResources()
    {
        $viewDirs = [];
        foreach ($this->pluginClasses as $pluginClass) {
            $viewDirs = array_merge(
                $viewDirs,
                $pluginClass->setViewResources()->getViews()
            );
        }

        return $viewDirs;
    }

    /**
     * @return array
     */
    public function getCssResources()
    {
        $cssFiles = [
            'frontend' => [],
            'backend' => [],
        ];
        foreach ($this->pluginClasses as $pluginClass) {
            $cssFiles['frontend'] = array_merge(
                $cssFiles['frontend'],
                $pluginClass->setCssResources()->getFrontendAssets()
            );
            $cssFiles['backend'] = array_merge(
                $cssFiles['backend'],
                $pluginClass->setCssResources()->getBackendAssets()
            );
        }

        return $cssFiles;
    }

    /**
     * @return array
     */
    public function getJsResources()
    {
        $jsFiles = [
            'frontend' => [],
            'backend' => [],
        ];
        foreach ($this->pluginClasses as $pluginClass) {
            $jsFiles['frontend'] = array_merge(
                $jsFiles['frontend'],
                $pluginClass->setJsResources()->getFrontendAssets()
            );
            $jsFiles['backend'] = array_merge(
                $jsFiles['backend'],
                $pluginClass->setJsResources()->getBackendAssets()
            );
        }

        return $jsFiles;
    }

    /**
     * @param $str string
     * @return string
     */
    private function classNameToUnderscore($str) {
        $str[0] = strtolower($str[0]);
        $func = create_function('$c', 'return "_" . strtolower($c[1]);');
        return preg_replace_callback('/([A-Z])/', $func, $str);
    }
}