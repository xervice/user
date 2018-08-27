<?php

namespace Xervice\User\Business\Model\Writer;

use DataProvider\UserLoginDataProvider;

interface LoginWriterInterface
{
    /**
     * @param \DataProvider\UserLoginDataProvider $loginDataProvider
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function updateLogin(UserLoginDataProvider $loginDataProvider): void;
}