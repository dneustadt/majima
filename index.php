<?php
/**
 * Copyright (c) 2017
 *
 * @package   Majima
 * @author    David Neustadt <kontakt@davidneustadt.de>
 * @copyright 2017 David Neustadt
 * @license   MIT
 */

use Symfony\Component\HttpFoundation\Request;
use Majima\MajimaKernel;

const BASE_DIR = __DIR__ . DIRECTORY_SEPARATOR;
const VERSION = "0.1.0";

require_once(BASE_DIR . join(DIRECTORY_SEPARATOR, ['vendor', 'autoload.php']));

$environment = getenv('MAJIMA_ENV') ? : 'prod';

if ($environment !== 'prod') {
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
}

$kernel = new MajimaKernel($environment, $environment !== 'prod');
$kernel->boot();

$request = Request::createFromGlobals();

$response = $kernel->handle($request);

$response->send();
$kernel->terminate($request, $response);