<?php
declare(strict_types=1);


namespace Xervice\User;


use DataProvider\UserDataProvider;
use Xervice\Core\Facade\AbstractFacade;

/**
 * @method \Xervice\User\UserFactory getFactory()
 * @method \Xervice\User\UserConfig getConfig()
 * @method \Xervice\User\UserClient getClient()
 */
class UserFacade extends AbstractFacade
{
    /**
     * @param \DataProvider\UserDataProvider $userDataProvider
     *
     * @return \DataProvider\UserDataProvider
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Xervice\User\Business\Exception\UserException
     */
    public function createUser(UserDataProvider $userDataProvider): UserDataProvider
    {
        return $this->getFactory()->createUserWriter()->createUser($userDataProvider);
    }

    /**
     * @param int $userId
     *
     * @return \DataProvider\UserDataProvider
     */
    public function getUserFromId(int $userId): UserDataProvider
    {
        return $this->getFactory()->getQueryContainer()->getUserFromId($userId);
    }

    /**
     * @param string $email
     *
     * @return \DataProvider\UserDataProvider
     */
    public function getUserFromEmail(string $email): UserDataProvider
    {
        return $this->getFactory()->getQueryContainer()->getUserFromEmail($email);
    }

    /**
     * @param \DataProvider\UserDataProvider $userDataProvider
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function deleteUser(UserDataProvider $userDataProvider): void
    {
        $this->getFactory()->createUserWriter()->deleteUser($userDataProvider);
    }
}