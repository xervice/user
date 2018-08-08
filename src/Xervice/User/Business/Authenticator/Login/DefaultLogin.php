<?php


namespace Xervice\User\Business\Authenticator\Login;


use DataProvider\UserAuthDataProvider;
use DataProvider\UserCredentialDataProvider;
use Xervice\User\Business\Authenticator\UserCredentialProviderInterface;

class DefaultLogin implements LoginInterface
{
    /**
     * @var \Xervice\User\Business\Authenticator\UserCredentialProviderInterface
     */
    private $authenticator;

    /**
     * DefaultLogin constructor.
     *
     * @param \Xervice\User\Business\Authenticator\UserCredentialProviderInterface $authenticator
     */
    public function __construct(UserCredentialProviderInterface $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    /**
     * @param \DataProvider\UserAuthDataProvider $authDataProvider
     * @param \DataProvider\UserCredentialDataProvider $userCredentialDataProvider
     *
     * @return bool
     */
    public function auth(
        UserAuthDataProvider $authDataProvider,
        UserCredentialDataProvider $userCredentialDataProvider
    ): bool {
        return password_verify($authDataProvider->getCredential()->getHash(), $userCredentialDataProvider->getHash());
    }


}