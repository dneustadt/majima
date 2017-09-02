<?php
/**
 * Copyright (c) 2017
 *
 * @package   Majima
 * @author    David Neustadt <kontakt@davidneustadt.de>
 * @copyright 2017 David Neustadt
 * @license   MIT
 */

namespace Majima\Security;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * Class MajimaAdminProvider
 * @package Majima\Security
 */
class MajimaAdminProvider implements UserProviderInterface
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var \FluentPDO
     */
    private $dbal;

    /**
     * MajimaAdminProvider constructor.
     * @param Container $container
     * @param \FluentPDO $dbal
     */
    public function __construct(Container $container, \FluentPDO $dbal)
    {
        $this->container = $container;
        $this->dbal = $dbal;
    }

    /**
     * @param string $username
     * @return MajimaAdmin
     * @throws \Exception
     */
    public function loadUserByUsername($username)
    {
        $user = $this->dbal
            ->from('users')
            ->where('name', $username)
            ->fetch();

        if ($user) {
            return new MajimaAdmin($username, $user['password'], $user['salt'], ['ROLE_ADMIN']);
        }

        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }

    /**
     * @param UserInterface $user
     * @return MajimaAdmin
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof MajimaAdmin) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return MajimaAdmin::class === $class;
    }
}