<?php
declare(strict_types=1);


namespace Xervice\User;


use Xervice\Core\Dependency\DependencyProviderInterface;
use Xervice\Core\Dependency\Provider\AbstractProvider;

/**
 * @method \Xervice\Core\Locator\Locator getLocator()
 */
class UserDependencyProvider extends AbstractProvider
{
    public const QUERY_CONTAINER = 'query.container';

    public const LOGIN_PLUGINS = 'login.plugins';

    /**
     * @param \Xervice\Core\Dependency\DependencyProviderInterface $dependencyProvider
     */
    public function handleDependencies(DependencyProviderInterface $dependencyProvider): void
    {
        $dependencyProvider[self::QUERY_CONTAINER] = function (DependencyProviderInterface $dependencyProvider) {
            return $dependencyProvider->getLocator()->user()->queryContainer();
        };

        $dependencyProvider[self::LOGIN_PLUGINS] = function () {
            return $this->getLoginPluginList();
        };
    }

    /**
     * type => {Login::class}
     *
     * @return \Xervice\User\Business\Authenticator\Login\LoginInterface[]
     */
    protected function getLoginPluginList(): array
    {
        return [];
    }
}