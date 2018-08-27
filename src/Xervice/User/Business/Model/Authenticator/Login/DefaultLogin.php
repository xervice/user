<?php


namespace Xervice\User\Business\Model\Authenticator\Login;


use DataProvider\UserAuthDataProvider;
use DataProvider\UserCredentialDataProvider;
use Xervice\User\Business\Dependency\Authenticator\Login\LoginInterface;

class DefaultLogin implements LoginInterface
{
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