<?php

namespace Xervice\User\Business\Model\Login;

use DataProvider\UserAuthDataProvider;
use DataProvider\UserDataProvider;

interface LoginHandlerInterface
{
    /**
     * @param \DataProvider\UserAuthDataProvider $authDataProvider
     *
     * @return \DataProvider\UserDataProvider
     */
    public function login(UserAuthDataProvider $authDataProvider): UserDataProvider;

    public function logout(): void;

    /**
     * @return \DataProvider\UserDataProvider|null
     */
    public function getUser(): ?UserDataProvider;
}