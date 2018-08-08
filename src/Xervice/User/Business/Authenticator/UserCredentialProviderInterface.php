<?php

namespace Xervice\User\Business\Authenticator;

use DataProvider\UserCredentialDataProvider;
use DataProvider\UserDataProvider;

interface UserCredentialProviderInterface
{
    /**
     * @param \DataProvider\UserDataProvider $userDataProvider
     * @param string $type
     *
     * @return \DataProvider\UserCredentialDataProvider
     * @throws \Xervice\User\Business\Exception\UserException
     */
    public function getCredentialsForType(UserDataProvider $userDataProvider, string $type): UserCredentialDataProvider;
}