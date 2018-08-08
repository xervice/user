<?php

namespace Xervice\User\Business\Validator;

use DataProvider\UserDataProvider;

interface UserValidatorInterface
{
    /**
     * @param \DataProvider\UserDataProvider $userDataProvider
     *
     * @throws \Xervice\User\Business\Exception\UserException
     */
    public function validateUser(UserDataProvider $userDataProvider);
}