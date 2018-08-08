<?php


namespace Xervice\User;


use DataProvider\UserDataProvider;
use Orm\Xervice\User\Persistence\User;
use Orm\Xervice\User\Persistence\UserQuery;
use Propel\Runtime\Map\TableMap;
use Xervice\Core\ServiceClass\XerviceInterface;

class UserQueryContainer implements XerviceInterface, UserQueryContainerInterface
{
    /**
     * @return \Orm\Xervice\User\Persistence\UserQuery
     */
    public function getUserQuery(): UserQuery
    {
        return UserQuery::create();
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
     *
     * @return $this|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function getUserQueryFromUserId(int $userId)
    {
        return $this->getUserQuery()
                          ->filterByUserId($userId)
                          ->joinWith('User.UserLogin')
                          ->joinWith('UserLogin.UserCredential');
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
                          ->joinWith('UserLogin.UserCredential');
    }

    /**
     * @param \Orm\Xervice\User\Persistence\User $user
     *
     * @return \DataProvider\UserDataProvider
     */
    private function getUserDataProviderFromEntity(User $user = null): UserDataProvider
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
}