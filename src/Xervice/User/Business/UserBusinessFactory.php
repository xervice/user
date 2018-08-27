<?php
declare(strict_types=1);


namespace Xervice\User\Business;


use Xervice\Core\Business\Model\Factory\AbstractBusinessFactory;
use Xervice\Session\Business\SessionFacade;
use Xervice\User\Business\Model\Authenticator\AuthProvider;
use Xervice\User\Business\Model\Authenticator\AuthProviderInterface;
use Xervice\User\Business\Model\Authenticator\UserCredentialProvider;
use Xervice\User\Business\Model\Authenticator\UserCredentialProviderInterface;
use Xervice\User\Business\Model\Login\LoginHandler;
use Xervice\User\Business\Model\Login\LoginHandlerInterface;
use Xervice\User\Business\Model\Validator\UserValidator;
use Xervice\User\Business\Model\Validator\UserValidatorInterface;
use Xervice\User\Business\Model\Writer\CredentialWriter;
use Xervice\User\Business\Model\Writer\CredentialWriterInterface;
use Xervice\User\Business\Model\Writer\LoginWriter;
use Xervice\User\Business\Model\Writer\LoginWriterInterface;
use Xervice\User\Business\Model\Writer\UserWriter;
use Xervice\User\Business\Model\Writer\UserWriterInterface;
use Xervice\User\UserDependencyProvider;

/**
 * @method \Xervice\User\Persistence\UserDataReader getReader()
 */
class UserBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Xervice\User\Business\Model\Writer\CredentialWriterInterface
     */
    public function createCredentialWriter(): CredentialWriterInterface
    {
        return new CredentialWriter(
            $this->getReader()
        );
    }

    /**
     * @return \Xervice\User\Business\Model\Writer\LoginWriterInterface
     */
    public function createLoginWriter(): LoginWriterInterface
    {
        return new LoginWriter(
            $this->getReader()
        );
    }

    /**
     * @return \Xervice\User\Business\Model\Login\LoginHandlerInterface
     */
    public function createLoginHandler(): LoginHandlerInterface
    {
        return new LoginHandler(
            $this->getSessionFacade(),
            $this->createAuthProvider()
        );
    }

    /**
     * @return \Xervice\User\Business\Model\Authenticator\AuthProviderInterface
     */
    public function createAuthProvider(): AuthProviderInterface
    {
        return new AuthProvider(
            $this->createCredentialProvider(),
            $this->getLoginPluginList()
        );
    }

    /**
     * @return \Xervice\User\Business\Model\Authenticator\UserCredentialProviderInterface
     */
    public function createCredentialProvider(): UserCredentialProviderInterface
    {
        return new UserCredentialProvider(
            $this->getReader()
        );
    }

    /**
     * @return \Xervice\User\Business\Model\Writer\UserWriterInterface
     */
    public function createUserWriter(): UserWriterInterface
    {
        return new UserWriter(
            $this->createUserValidator(),
            $this->getReader()
        );
    }

    /**
     * @return \Xervice\User\Business\Model\Validator\UserValidatorInterface
     */
    public function createUserValidator(): UserValidatorInterface
    {
        return new UserValidator(
            $this->getReader()
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
     * @return \Xervice\Session\Business\SessionFacade
     */
    public function getSessionFacade(): SessionFacade
    {
        return $this->getDependency(UserDependencyProvider::SESSION_FACADE);
    }
}