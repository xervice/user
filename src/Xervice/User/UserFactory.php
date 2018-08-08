<?php
declare(strict_types=1);


namespace Xervice\User;


use Xervice\Core\Factory\AbstractFactory;
use Xervice\Session\SessionClient;
use Xervice\User\Business\Authenticator\AuthProvider;
use Xervice\User\Business\Authenticator\AuthProviderInterface;
use Xervice\User\Business\Authenticator\UserCredentialProvider;
use Xervice\User\Business\Authenticator\UserCredentialProviderInterface;
use Xervice\User\Business\Login\LoginHandler;
use Xervice\User\Business\Login\LoginHandlerInterface;
use Xervice\User\Business\Validator\UserValidator;
use Xervice\User\Business\Validator\UserValidatorInterface;
use Xervice\User\Business\Writer\UserWriter;
use Xervice\User\Business\Writer\UserWriterInterface;

/**
 * @method \Xervice\User\UserConfig getConfig()
 */
class UserFactory extends AbstractFactory
{
    /**
     * @return \Xervice\User\Business\Login\LoginHandlerInterface
     */
    public function createLoginHandler(): LoginHandlerInterface
    {
        return new LoginHandler(
            $this->getSessionClient(),
            $this->createAuthProvider()
        );
    }

    /**
     * @return \Xervice\User\Business\Authenticator\AuthProviderInterface
     */
    public function createAuthProvider(): AuthProviderInterface
    {
        return new AuthProvider(
            $this->createCredentialProvider(),
            $this->getLoginPluginList()
        );
    }

    /**
     * @return \Xervice\User\Business\Authenticator\UserCredentialProviderInterface
     */
    public function createCredentialProvider(): UserCredentialProviderInterface
    {
        return new UserCredentialProvider(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Xervice\User\Business\Writer\UserWriterInterface
     */
    public function createUserWriter(): UserWriterInterface
    {
        return new UserWriter(
            $this->createUserValidator(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Xervice\User\Business\Validator\UserValidatorInterface
     */
    public function createUserValidator(): UserValidatorInterface
    {
        return new UserValidator(
            $this->getQueryContainer()
        );
    }

    /**
     * @return array
     */
    public function getLoginPluginList(): array
    {
        return $this->getDependency(UserDependencyProvider::LOGIN_PLUGINS);
    }

    /**
     * @return \Xervice\User\UserQueryContainerInterface
     */
    public function getQueryContainer(): UserQueryContainerInterface
    {
        return $this->getDependency(UserDependencyProvider::QUERY_CONTAINER);
    }

    /**
     * @return \Xervice\Session\SessionClient
     */
    public function getSessionClient(): SessionClient
    {
        return $this->getDependency(UserDependencyProvider::SESSION_CLIENT);
    }
}