<?php

namespace Xervice\User;

use DataProvider\UserCredentialDataProvider;
use DataProvider\UserDataProvider;
use DataProvider\UserLoginDataProvider;
use Orm\Xervice\User\Persistence\User;
use Orm\Xervice\User\Persistence\UserCredential;
use Orm\Xervice\User\Persistence\UserCredentialQuery;
use Orm\Xervice\User\Persistence\UserLogin;
use Orm\Xervice\User\Persistence\UserLoginQuery;
use Orm\Xervice\User\Persistence\UserQuery;

interface UserQueryContainerInterface
{
    /**
     * @return \Orm\Xervice\User\Persistence\UserQuery
     */
    public function getUserQuery(): UserQuery;

    /**
     * @return \Orm\Xervice\User\Persistence\UserLoginQuery
     */
    public function getLoginQuery(): UserLoginQuery;

    /**
     * @return \Orm\Xervice\User\Persistence\UserCredentialQuery
     */
    public function getCredentialQuery(): UserCredentialQuery;

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
     * @param string $type
     *
     * @return \DataProvider\UserLoginDataProvider
     */
    public function getUserLoginFromType(int $userId, string $type): UserLoginDataProvider;

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
     * @param int $userId
     * @param string $type
     *
     * @return $this|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function getUserLoginQueryFromType(int $userId, string $type);

    /**
     * @param \Orm\Xervice\User\Persistence\UserCredential $credential
     *
     * @return \DataProvider\UserCredentialDataProvider
     */
    public function getCredentialDataProviderFromEntity(UserCredential $credential): UserCredentialDataProvider;

    /**
     * @param \Orm\Xervice\User\Persistence\UserLogin $login
     *
     * @return \DataProvider\UserLoginDataProvider
     */
    public function getLoginDataProviderFromEntity(UserLogin $login): UserLoginDataProvider;

    /**
     * @param \Orm\Xervice\User\Persistence\User $user
     *
     * @return \DataProvider\UserDataProvider
     */
    public function getUserDataProviderFromEntity(User $user = null): UserDataProvider;

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