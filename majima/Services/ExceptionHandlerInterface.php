<?php

namespace Majima\Services;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * Interface ExceptionHandlerInterface
 * @package Majima\Services
 */
interface ExceptionHandlerInterface
{
    /**
     * @param GetResponseForExceptionEvent $event
     * @return mixed
     */
    public function onKernelException(GetResponseForExceptionEvent $event);
}