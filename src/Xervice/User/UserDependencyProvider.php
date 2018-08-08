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

    /**
     * @param \Xervice\Core\Dependency\DependencyProviderInterface $dependencyProvider
     */
    public function handleDependencies(DependencyProviderInterface $dependencyProvider): void
    {
        $dependencyProvider[self::QUERY_CONTAINER] = function (DependencyProviderInterface $dependencyProvider) {
            return $dependencyProvider->getLocator()->user()->queryContainer();
        };
    }
}