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

use Symfony\Component\HttpFoundation\Response;

/**
 * Interface DwooEngineFactoryInterface
 * @package Majima\Services
 */
interface DwooEngineFactoryInterface
{
    public function getData();

    /**
     * @param array $data
     */
    public function setData($data);

    /**
     * @param string $template
     * @param array $data
     * @param int $status
     * @param string $contentType
     * @return Response
     */
    public function render($template, $data, $status, $contentType);
}