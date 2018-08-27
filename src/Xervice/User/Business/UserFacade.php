<?php
declare(strict_types=1);


namespace Xervice\User\Business;


use DataProvider\UserAuthDataProvider;
use DataProvider\UserCredentialDataProvider;
use DataProvider\UserDataProvider;
use DataProvider\UserLoginDataProvider;
use Xervice\Core\Business\Model\Facade\AbstractFacade;

/**
 * @method \Xervice\User\Business\UserBusinessFactory getFactory()
 */
class UserFacade extends AbstractFacade
{
    /**
     * @param \DataProvider\UserAuthDataProvider $authDataProvider
     *
     * @return \DataProvider\UserDataProvider
     * @throws \Xervice\User\Business\Exception\UserException
     */
    public function login(UserAuthDataProvider $authDataProvider)
    {
        return $this->getFactory()->createLoginHandler()->login($authDataProvider);
    }

    public function logout(): void
    {
        $this->getFactory()->createLoginHandler()->logout();
    }

    /**
     * @return \DataProvider\UserDataProvider|null
     */
    public function getLoggedUser(): ?UserDataProvider
    {
        return $this->getFactory()->createLoginHandler()->getUser();
    }

    /**
     * @param \DataProvider\UserAuthDataProvider $authDataProvider
     *
     * @return bool
     * @throws \Xervice\User\Business\Exception\UserException
     */
    public function auth(UserAuthDataProvider $authDataProvider): bool
    {
        return $this->getFactory()->createAuthProvider()->auth($authDataProvider);
    }

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
     * @param \DataProvider\UserDataProvider $userDataProvider
     *
     * @return \DataProvider\UserDataProvider
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Xervice\User\Business\Exception\UserException
     */
    public function updateUser(UserDataProvider $userDataProvider): UserDataProvider
    {
        return $this->getFactory()->createUserWriter()->updateUser($userDataProvider);
    }

    /**
     * @param \DataProvider\UserLoginDataProvider $loginDataProvider
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function updateLogin(UserLoginDataProvider $loginDataProvider): void
    {
        $this->getFactory()->createLoginWriter()->updateLogin($loginDataProvider);
    }

    /**
     * @param \DataProvider\UserCredentialDataProvider $credentialDataProvider
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function updateCredential(UserCredentialDataProvider $credentialDataProvider): void
    {
        $this->getFactory()->createCredentialWriter()->updateCredential($credentialDataProvider);
    }

    /**
     * @param int $userId
     *
     * @return \DataProvider\UserDataProvider
     */
    public function getUserFromId(int $userId): UserDataProvider
    {
        return $this->getFactory()->getReader()->getUserFromId($userId);
    }

    /**
     * @param string $email
     *
     * @return \DataProvider\UserDataProvider
     */
    public function getUserFromEmail(string $email): UserDataProvider
    {
        return $this->getFactory()->getReader()->getUserFromEmail($email);
    }

    /**
     * @param int $userId
     * @param string $type
     *
     * @return \DataProvider\UserLoginDataProvider
     */
    public function getLoginFromUserByType(int $userId, string $type): UserLoginDataProvider
    {
        return $this->getFactory()->getReader()->getUserLoginFromType($userId, $type);
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