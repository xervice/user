<?php

namespace Xervice\User\Business\Login;

use DataProvider\UserAuthDataProvider;
use DataProvider\UserDataProvider;

interface LoginHandlerInterface
{
    /**
     * @param \DataProvider\UserAuthDataProvider $authDataProvider
     *
     * @return \DataProvider\UserDataProvider
     * @throws \Xervice\User\Business\Exception\UserException
     */
    public function login(UserAuthDataProvider $authDataProvider);

    public function logout(): void;

    /**
     * @return \DataProvider\UserDataProvider|null
     */
    public function getUser(): ?UserDataProvider;
}