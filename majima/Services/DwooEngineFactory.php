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

use Dwoo\Core;
use Dwoo\Data;
use Dwoo\Template\File;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DwooEngineFactory
 * @package Majima\Services
 */
class DwooEngineFactory extends Core implements DwooEngineFactoryInterface
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var ConfigService
     */
    private $config;

    /**
     * @var Data
     */
    private $templateData;

    /**
     * DwooEngineFactory constructor.
     * @param Container $container
     * @param ConfigService $config
     */
    public function __construct(Container $container, ConfigService $config)
    {
        $this->container = $container;
        $this->config = $config;
        $this->templateData = new Data();

        parent::__construct();

        $this->addGlobal('service_container', $this->container);
        $this->setCompileDir($this->container->getParameter('kernel.cache_dir') . DIRECTORY_SEPARATOR . 'templates');
        $this->getLoader()->addDirectory(BASE_DIR . join(DIRECTORY_SEPARATOR, ['majima', 'Dwoo', 'Plugins']));
    }

    /**
     * @return Data
     */
    public function getData()
    {
        return $this->templateData;
    }

    /**
     * @param array $data
     */
    public function setData($data = [])
    {
        $this->templateData->assign($data);
    }

    /**
     * @param array|string $template
     * @param array $data
     * @param int $status
     * @param string $contentType
     * @return Response
     */
    public function render($template = '', $data = [], $status = 200, $contentType = 'text/html')
    {
        return $this->getResponse($template, $data, $status, $contentType);
    }

    /**
     * @param $template
     * @param $data
     * @param $status
     * @param $contentType
     * @return Response
     */
    public function getResponse($template, $data, $status, $contentType)
    {
        // set the base template dir
        $this->setTemplateDir(BASE_DIR . join(DIRECTORY_SEPARATOR, ['majima', 'Resources', 'views']));

        $this->setData($data);

        $templateFile = new File(
            $template,
            null,
            null,
            null,
            $this->getTemplateDir()
        );

        if (!in_array('parent', array_keys($this->getGlobals()))) {
            $this->addGlobal('parent', null);
        }
        foreach ($this->config->getArray() as $global => $value) {
            $this->addGlobal($global, $value);
        }

        return new Response(
            $this->get(
                $templateFile,
                $this->getData()
            ),
            $status,
            ['Content-Type' => $contentType]
        );
    }
}