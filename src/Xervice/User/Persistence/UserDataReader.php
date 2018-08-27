<?php
declare(strict_types=1);

namespace Xervice\User\Persistence;

use DataProvider\UserCredentialDataProvider;
use DataProvider\UserDataProvider;
use DataProvider\UserLoginDataProvider;
use Orm\Xervice\User\Persistence\User;
use Orm\Xervice\User\Persistence\UserCredential;
use Orm\Xervice\User\Persistence\UserCredentialQuery;
use Orm\Xervice\User\Persistence\UserLogin;
use Orm\Xervice\User\Persistence\UserLoginQuery;
use Orm\Xervice\User\Persistence\UserQuery;
use Propel\Runtime\Map\TableMap;
use Xervice\Core\Business\Model\Persistence\Reader\AbstractReader;

class UserDataReader extends AbstractReader
{
    /**
     * @return \Orm\Xervice\User\Persistence\UserQuery
     */
    public function getUserQuery(): UserQuery
    {
        return UserQuery::create();
    }

    /**
     * @return \Orm\Xervice\User\Persistence\UserLoginQuery
     */
    public function getLoginQuery(): UserLoginQuery
    {
        return UserLoginQuery::create();
    }

    /**
     * @return \Orm\Xervice\User\Persistence\UserCredentialQuery
     */
    public function getCredentialQuery(): UserCredentialQuery
    {
        return UserCredentialQuery::create();
    }

    /**
     * @param int $userId
     *
     * @return UserDataProvider
     */
    public function getUserFromId(int $userId): UserDataProvider
    {
        $userQuery = $this->getUserQueryFromUserId($userId);
        $users = $userQuery->find();
        $user = $users[0] ?? null;

        return $this->getUserDataProviderFromEntity($user);
    }

    /**
     * @param string $email
     *
     * @return UserDataProvider
     */
    public function getUserFromEmail(string $email): UserDataProvider
    {
        $userQuery = $this->getUserQueryFromEmail($email);
        $users = $userQuery->find();
        $user = $users[0] ?? null;

        return $this->getUserDataProviderFromEntity($user);
    }

    /**
     * @param int $userId
     * @param string $type
     *
     * @return \DataProvider\UserLoginDataProvider
     */
    public function getUserLoginFromType(int $userId, string $type): UserLoginDataProvider
    {
        $loginQuery = $this->getUserLoginQueryFromType($userId, $type);
        $logins = $loginQuery->find();
        $login = $logins[0] ?? null;

        return $this->getLoginDataProviderFromEntity($login);
    }

    /**
     * @param int $userId
     *
     * @return $this|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function getUserQueryFromUserId(int $userId)
    {
        return $this->getUserQuery()
                    ->filterByUserId($userId)
                    ->joinWith('User.UserLogin')
                    ->joinWith('UserLogin.UserCredential')
            ;
    }

    /**
     * @param string $email
     *
     * @return $this|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function getUserQueryFromEmail(string $email)
    {
        return $this->getUserQuery()
                    ->filterByEmail($email)
                    ->joinWith('User.UserLogin')
                    ->joinWith('UserLogin.UserCredential')
            ;
    }

    /**
     * @param int $userId
     * @param string $type
     *
     * @return $this|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function getUserLoginQueryFromType(int $userId, string $type)
    {
        return $this->getLoginQuery()
                    ->filterByUserId($userId)
                    ->filterByType($type)
                    ->joinWith('UserLogin.UserCredential');
    }


    /**
     * @param \Orm\Xervice\User\Persistence\UserCredential $credential
     *
     * @return \DataProvider\UserCredentialDataProvider
     */
    public function getCredentialDataProviderFromEntity(UserCredential $credential = null): UserCredentialDataProvider
    {
        $credentialDataProvider = new UserCredentialDataProvider();
        if ($credential) {
            $credentialDataProvider->fromArray(
                $credential->toArray(
                    TableMap::TYPE_PHPNAME,
                    true,
                    [],
                    true
                )
            );
        }

        return $credentialDataProvider;
    }

    /**
     * @param \Orm\Xervice\User\Persistence\UserLogin $login
     *
     * @return \DataProvider\UserLoginDataProvider
     */
    public function getLoginDataProviderFromEntity(UserLogin $login = null): UserLoginDataProvider
    {
        $loginDataProvider = new UserLoginDataProvider();
        if ($login) {
            $loginDataProvider->fromArray(
                $login->toArray(
                    TableMap::TYPE_PHPNAME,
                    true,
                    [],
                    true
                )
            );
        }

        return $loginDataProvider;
    }

    /**
     * @param \Orm\Xervice\User\Persistence\User $user
     *
     * @return \DataProvider\UserDataProvider
     */
    public function getUserDataProviderFromEntity(User $user = null): UserDataProvider
    {
        $userDataProvider = new UserDataProvider();
        if ($user) {
            $userDataProvider->fromArray(
                $user->toArray(
                    TableMap::TYPE_PHPNAME,
                    true,
                    [],
                    true
                )
            );
        }

        return $userDataProvider;
    }

    /**
     * @param \DataProvider\UserLoginDataProvider $loginDataProvider
     *
     * @return \Orm\Xervice\User\Persistence\UserLogin
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getLoginEntityFromDataProvider(UserLoginDataProvider $loginDataProvider): UserLogin
    {
        if ($loginDataProvider->hasUserLoginId()) {
            $userLoginEntity = $this->getLoginQuery()->findOneByUserLoginId($loginDataProvider->getUserLoginId());
        } else {
            $userLoginEntity = new UserLogin();
        }

        $userLoginEntity->fromArray($loginDataProvider->toArray());

        if ($loginDataProvider->hasUserCredential()) {
            $userLoginEntity->setUserCredential(
                $this->getUserCredentialsEntity($loginDataProvider->getUserCredential())
            );
        }

        return $userLoginEntity;
    }

    /**
     * @param $credentialDataProvider
     *
     * @return \Orm\Xervice\User\Persistence\UserCredential
     */
    public function getUserCredentialsEntity(UserCredentialDataProvider $credentialDataProvider): UserCredential
    {
        if ($credentialDataProvider->hasUserCredentialId()) {
            $userCredentialEntity = $this->getCredentialQuery()->findOneByUserCredentialId(
                $credentialDataProvider->getUserCredentialId()
            );
        } else {
            $userCredentialEntity = new UserCredential();
        }
        $userCredentialEntity->fromArray($credentialDataProvider->toArray());
        return $userCredentialEntity;
    }
}