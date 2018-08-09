<?php

namespace Xervice\User;

use DataProvider\UserCredentialDataProvider;
use DataProvider\UserDataProvider;
use DataProvider\UserLoginDataProvider;
use Orm\Xervice\User\Persistence\UserCredential;
use Orm\Xervice\User\Persistence\UserLogin;
use Orm\Xervice\User\Persistence\UserQuery;

interface UserQueryContainerInterface
{
    /**
     * @return \Orm\Xervice\User\Persistence\UserQuery
     */
    public function getUserQuery(): UserQuery;

    /**
     * @param int $userId
     *
     * @return UserDataProvider
     */
    public function getUserFromId(int $userId): UserDataProvider;

    /**
     * @param string $email
     *
     * @return UserDataProvider
     */
    public function getUserFromEmail(string $email): UserDataProvider;

    /**
     * @param int $userId
     *
     * @return $this|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function getUserQueryFromUserId(int $userId);

    /**
     * @param string $email
     *
     * @return $this|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function getUserQueryFromEmail(string $email);

    /**
     * @param \DataProvider\UserLoginDataProvider $loginDataProvider
     *
     * @return \Orm\Xervice\User\Persistence\UserLogin
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getLoginEntityFromDataProvider(UserLoginDataProvider $loginDataProvider): UserLogin;

    /**
     * @param $credentialDataProvider
     *
     * @return \Orm\Xervice\User\Persistence\UserCredential
     */
    public function getUserCredentialsEntity(UserCredentialDataProvider $credentialDataProvider): UserCredential;
}