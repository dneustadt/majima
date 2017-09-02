<?php
/**
 * Copyright (c) 2017
 *
 * @package   Majima
 * @author    David Neustadt <kontakt@davidneustadt.de>
 * @copyright 2017 David Neustadt
 * @license   MIT
 */

namespace Majima\Controller;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class AdminController
 * @package Majima\Controller
 */
class AdminController
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * AdminController constructor.
     * @param Container $container
     * @param RouterInterface $router
     */
    public function __construct(Container $container, RouterInterface $router)
    {
        $this->container = $container;
        $this->router = $router;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function clearcacheAction(Request $request)
    {
        $fs = new Filesystem();
        $fs->remove($this->container->getParameter('kernel.cache_dir'));

        $cssPath = BASE_DIR . join(DIRECTORY_SEPARATOR, ['web', 'css', 'style.min.css']);
        $backendCssPath = BASE_DIR . join(DIRECTORY_SEPARATOR, ['web', 'css', 'style.backend.min.css']);
        $jsPath = BASE_DIR . join(DIRECTORY_SEPARATOR, ['web', 'js', 'scripts.min.js']);
        $backendJsPath = BASE_DIR . join(DIRECTORY_SEPARATOR, ['web', 'js', 'scripts.backend.min.js']);

        if (file_exists($cssPath)) {
            $fs->remove($cssPath);
        }

        if (file_exists($backendCssPath)) {
            $fs->remove($backendCssPath);
        }

        if (file_exists($jsPath)) {
            $fs->remove($jsPath);
        }

        if (file_exists($backendJsPath)) {
            $fs->remove($backendJsPath);
        }

        return new RedirectResponse($this->router->generate('index_index'));
    }
}