<?php
declare(strict_types=1);


namespace Xervice\User;


use Xervice\Core\Factory\AbstractFactory;
use Xervice\User\Business\Authenticator\UserAuthenticator;
use Xervice\User\Business\Authenticator\UserAuthenticatorInterface;
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
     * @return \Xervice\User\Business\Authenticator\UserAuthenticatorInterface
     */
    public function createAuthenticator(): UserAuthenticatorInterface
    {
        return new UserAuthenticator(
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
        return new UserValidator();
    }

    /**
     * @return \Xervice\User\UserQueryContainerInterface
     */
    public function getQueryContainer(): UserQueryContainerInterface
    {
        return $this->getDependency(UserDependencyProvider::QUERY_CONTAINER);
    }
}