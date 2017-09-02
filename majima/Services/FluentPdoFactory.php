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
use Symfony\Component\DependencyInjection\Container;

/**
 * Class FluentPdoFactory
 * @package Majima\Services
 */
class FluentPdoFactory extends \FluentPDO
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var \PDO
     */
    private $PDO;

    /**
     * FluentPdoFactory constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        if ($container->hasParameter('db_config_loaded')) {
            $this->PDO = new \PDO(
                sprintf(
                    "mysql:host=%s;port=%s;dbname=%s",
                    $container->getParameter('db_host'),
                    $container->getParameter('db_port'),
                    $container->getParameter('db_name')
                ),
                $container->getParameter('db_user'),
                $container->getParameter('db_passw')
            );

            parent::__construct($this->PDO);
        }
    }
}