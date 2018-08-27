<?php
declare(strict_types=1);


namespace Xervice\User;



use Xervice\Core\Business\Model\Dependency\DependencyContainerInterface;
use Xervice\Core\Business\Model\Dependency\Provider\AbstractDependencyProvider;

class UserDependencyProvider extends AbstractDependencyProvider
{
    public const LOGIN_PLUGINS = 'login.plugins';
    public const SESSION_FACADE = 'session.client';

    public function handleDependencies(DependencyContainerInterface $container): DependencyContainerInterface
    {
        $container[self::LOGIN_PLUGINS] = function () {
            return $this->getLoginPluginList();
        };

        $container[self::SESSION_FACADE] = function (DependencyContainerInterface $container) {
            return $container->getLocator()->session()->facade();
        };

        return $container;
    }

    /**
     * type => {Login::class}
     *
     * @return \Xervice\User\Business\Dependency\Authenticator\Login\LoginInterface[]
     */
    protected function getLoginPluginList(): array
    {
        return [];
    }
}