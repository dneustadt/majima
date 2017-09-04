<?php

namespace Majima\Services;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class ExceptionHandler
 * @package Golli\Services
 */
class ExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * @var Container
     */
    private $container;

    /**
     * ExceptionHandler constructor.
     * @param Container $container
     */
    public function __construct(
        Container $container
    )
    {
        $this->container = $container;
    }

    /**
     * @var GetResponseForExceptionEvent $event
     * @return void
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        /** @var DwooEngineFactory $engine */
        $engine = $this->container->get('dwoo.engine');

        /**
         * @var TokenStorage $tokenStorage
         */
        $tokenStorage = $this->container->get('security.token_storage');
        $tokenStorage->setToken(new AnonymousToken('default', 'anon.'));

        // If not a HttpNotFoundException ignore
        if ($event->getException() instanceof NotFoundHttpException) {
            $response = $engine->render('Exception/404.tpl');
            $response->setStatusCode(400);
            $event->setResponse(
                $response
            );
            return;
        }

        $engine->setData([
            "errorMessage" => $event->getException()->getMessage(),
            "exception" => $event->getException()->getTraceAsString()
        ]);
        $response = $engine->render('Exception/error.tpl');
        $response->setStatusCode(400);
        $event->setResponse(
            $response
        );
    }
}