<?php

namespace Xervice\User\Business\Model\Validator;

use DataProvider\UserDataProvider;

interface UserValidatorInterface
{
    /**
     * @param \DataProvider\UserDataProvider $userDataProvider
     */
    public function validateUser(UserDataProvider $userDataProvider);
}