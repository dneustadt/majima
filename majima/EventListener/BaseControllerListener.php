<?php
/**
 * Copyright (c) 2017
 *
 * @package   Majima
 * @author    David Neustadt <kontakt@davidneustadt.de>
 * @copyright 2017 David Neustadt
 * @license   MIT
 */

namespace Majima\EventListener;

use Majima\Services\DwooEngineFactory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Majima\MajimaController;

/**
 * Class BaseControllerListener
 * @package Majima\EventListener
 */
class BaseControllerListener implements EventSubscriberInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var Container
     */
    private $container;

    /**
     * BaseControllerListener constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param Container $container
     */
    public function __construct(TokenStorageInterface $tokenStorage, Container $container)
    {
        $this->tokenStorage = $tokenStorage;
        $this->container = $container;
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        /** @var Controller $controller */
        $controller = $event->getController();
        if ($this->isBaseController($controller)) {
            $controllerAction = explode(':', $event->getRequest()->attributes->get('_controller'));
            call_user_func(
                [$controller[0], $controllerAction[1]],
                $event->getRequest()
            );
            /** @var DwooEngineFactory $engine */
            $engine = $this->container->get('dwoo.engine');
            $tpl = ucfirst(str_replace('_', '/', $event->getRequest()->attributes->get('_route')));
            $event->setController(function() use ($engine, $tpl) {
                return $engine->render(
                    $tpl . '.tpl'
                );
            });
        }
    }

    /**
     * @param $controller
     * @return bool
     */
    protected function isBaseController($controller)
    {
        if (!is_array($controller)) {
            return false;
        }

        return $controller[0] instanceof MajimaController;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}