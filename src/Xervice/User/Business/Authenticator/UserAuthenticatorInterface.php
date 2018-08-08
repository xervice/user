<?php

namespace Xervice\User\Business\Authenticator;

interface UserAuthenticatorInterface
{
    /**
     * @param string $email
     * @param string $type
     * @param string $hash
     *
     * @return bool
     * @throws \Xervice\User\Business\Exception\UserException
     */
    public function authenticate(string $email, string $type, string $hash): bool;
}