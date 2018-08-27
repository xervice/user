<?php

namespace Xervice\User\Business\Model\Authenticator;

use DataProvider\UserCredentialDataProvider;
use DataProvider\UserDataProvider;

interface UserCredentialProviderInterface
{
    /**
     * @param \DataProvider\UserDataProvider $userDataProvider
     * @param string $type
     *
     * @return \DataProvider\UserCredentialDataProvider
     */
    public function getCredentialsForType(UserDataProvider $userDataProvider, string $type): UserCredentialDataProvider;
}