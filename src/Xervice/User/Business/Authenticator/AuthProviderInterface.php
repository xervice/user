<?php

namespace Xervice\User\Business\Authenticator;

use DataProvider\UserAuthDataProvider;

interface AuthProviderInterface
{
    /**
     * @param string $type
     * @param \DataProvider\UserAuthDataProvider $authDataProvider
     *
     * @return bool
     * @throws \Xervice\User\Business\Exception\UserException
     */
    public function auth(UserAuthDataProvider $authDataProvider): bool;
}