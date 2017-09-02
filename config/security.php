<?php
/**
 * Copyright (c) 2017
 *
 * @package   Majima
 * @author    David Neustadt <kontakt@davidneustadt.de>
 * @copyright 2017 David Neustadt
 * @license   MIT
 */

$container->loadFromExtension('security', [
    'providers' => [
        'majima' => [
            'id' => 'majima.admin_provider',
        ],
    ],
    'firewalls' => [
        'default' => [
            'pattern' => '^/',
            'anonymous' => true,
            'form_login' => [
                'login_path' => '/',
                'check_path' => '/admin/login/'
            ],
            'logout' => [
                'path' => '/admin/logout/',
                'target' => '/'
            ],
        ],
        'secure' => [
            'pattern' => '^/admin',
            'form_login' => [
                'login_path' => '/',
                'check_path' => '/admin/login/'
            ],
            'logout' => [
                'path' => '/admin/logout/',
                'target' => '/'
            ],
        ],
    ],
    'access_control' => [
        ['path' => '^/admin/', 'roles' => 'ROLE_ADMIN'],
        ['path' => '^/admin/login/', 'role' => 'IS_AUTHENTICATED_ANONYMOUSLY'],
    ],
    'encoders' => [
        \Majima\Security\MajimaAdmin::class => [
            'algorithm' => 'sha512',
            'encode_as_base64' => true,
            'iterations' => 5000
        ],
    ],
]);