<?php
/**
 * Copyright (c) 2017
 *
 * @package   Majima
 * @author    David Neustadt <kontakt@davidneustadt.de>
 * @copyright 2017 David Neustadt
 * @license   MIT
 */

$container->loadFromExtension('framework', [
    'secret' => sha1(BASE_DIR),
    'session' => [
        'save_path' => null
    ],
    'router' => [
        'resource' => __DIR__ . "/routing.php"
    ]
]);