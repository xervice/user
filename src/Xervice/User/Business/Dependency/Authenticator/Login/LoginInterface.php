<?php


namespace Xervice\User\Business\Dependency\Authenticator\Login;


use DataProvider\UserAuthDataProvider;
use DataProvider\UserCredentialDataProvider;

interface LoginInterface
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
    ): bool;
}