<?php

namespace Xervice\User\Business\Writer;

use DataProvider\UserDataProvider;
use DataProvider\UserLoginDataProvider;

interface UserWriterInterface
{
    /**
     * @param \DataProvider\UserDataProvider $userDataProvider
     *
     * @return \DataProvider\UserDataProvider
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Xervice\User\Business\Exception\UserException
     */
    public function createUser(UserDataProvider $userDataProvider);

    /**
     * @param \DataProvider\UserDataProvider $userDataProvider
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function deleteUser(UserDataProvider $userDataProvider): void;

    /**
     * @param \DataProvider\UserDataProvider $userDataProvider
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Xervice\User\Business\Exception\UserException
     */
    public function addLoginToUser(
        UserDataProvider $userDataProvider,
        UserLoginDataProvider $loginDataProvider
    ): void;
}