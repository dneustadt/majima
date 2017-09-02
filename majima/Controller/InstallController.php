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
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

/**
 * Class InstallController
 * @package Majima\Controller
 */
class InstallController
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
     * InstallController constructor.
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
    public function indexAction(Request $request)
    {
        if ($this->container->hasParameter('db_config_loaded')) {
            return new RedirectResponse($this->router->generate('index_index'));
        }

        $template = file_get_contents(BASE_DIR . join(DIRECTORY_SEPARATOR, ['majima', 'Resources', 'views', 'Install', 'index.html']));

        $params = [
            'db_host' => $request->get('db_host'),
            'db_port' => $request->get('db_port'),
            'db_name' => $request->get('db_name'),
            'db_user' => $request->get('db_user'),
            'db_passw' => $request->get('db_passw'),
            'admin_user' => $request->get('admin_user'),
            'admin_passw' => $request->get('admin_passw'),
        ];

        foreach ($params as $param) {
            if (empty($param)) {
                return new Response(
                    $template,
                    200,
                    ['Content-Type' => 'text/html']
                );
            }
        }

        $defaultEncoder = new MessageDigestPasswordEncoder('sha512', true, 5000);
        $adminUser = $params['admin_user'];
        $adminSha1 = sha1(rand(100000, 999999));
        $adminPassword = $defaultEncoder->encodePassword($params['admin_passw'], $adminSha1);

        unset($params['admin_user']);
        unset($params['admin_passw']);

        $configPath = BASE_DIR . join(DIRECTORY_SEPARATOR, ['var', 'conf', 'config.json']);

        file_put_contents($configPath, json_encode($params, JSON_PRETTY_PRINT));

        $fs = new Filesystem();
        $fs->remove($this->container->getParameter('kernel.cache_dir'));

        $sql = file_get_contents(BASE_DIR . join(DIRECTORY_SEPARATOR, ['majima', 'Resources', 'sql', 'install.sql']));

        $pdo = new \PDO(
            sprintf(
                "mysql:host=%s;port=%s;dbname=%s",
                $params['db_host'],
                $params['db_port'],
                $params['db_name']
            ),
            $params['db_user'],
            $params['db_passw']
        );
        $pdo->exec($sql);

        $qb = new \FluentPDO($pdo);
        $qb->insertInto('users')
            ->values([
                'name' => $adminUser,
                'password' => $adminPassword,
                'salt' => $adminSha1
            ])
            ->execute();

        $template = file_get_contents(BASE_DIR . join(DIRECTORY_SEPARATOR, ['majima', 'Resources', 'views', 'Install', 'success.html']));
        return new Response(
            $template,
            200,
            ['Content-Type' => 'text/html']
        );
    }
}