<?php


namespace Xervice\User\Business\Writer;


use DataProvider\UserDataProvider;
use DataProvider\UserLoginDataProvider;
use Orm\Xervice\User\Persistence\User;
use Orm\Xervice\User\Persistence\UserCredential;
use Orm\Xervice\User\Persistence\UserLogin;
use Xervice\User\Business\Exception\UserException;
use Xervice\User\Business\Validator\UserValidatorInterface;
use Xervice\User\UserQueryContainerInterface;

class UserWriter implements UserWriterInterface
{
    /**
     * @var \Xervice\User\Business\Validator\UserValidatorInterface
     */
    private $validator;

    /**
     * @var \Xervice\User\UserQueryContainerInterface
     */
    private $queryContainer;

    /**
     * UserWriter constructor.
     *
     * @param \Xervice\User\Business\Validator\UserValidatorInterface $validator
     * @param \Xervice\User\UserQueryContainerInterface $queryContainer
     */
    public function __construct(
        UserValidatorInterface $validator,
        UserQueryContainerInterface $queryContainer
    ) {
        $this->validator = $validator;
        $this->queryContainer = $queryContainer;
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
        $this->validator->validateUser($userDataProvider);

        $user = new User();
        $user = $this->hydrateEntityFromDataProvider($user, $userDataProvider);
        $user->save();

        return $userDataProvider->setUserId($user->getUserId());
    }

    /**
     * @param \DataProvider\UserDataProvider $userDataProvider
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Xervice\User\Business\Exception\UserException
     */
    public function addLoginToUser(
        UserDataProvider $userDataProvider,
        UserLoginDataProvider $loginDataProvider
    ): void {
        $user = $this->queryContainer->getUserQuery()->findOneByUserId($userDataProvider->getUserId());

        $user->addUserLogin(
            $this->getUserLoginEntity($loginDataProvider)
        );

        $user->save();
    }

    /**
     * @param \DataProvider\UserDataProvider $userDataProvider
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function deleteUser(UserDataProvider $userDataProvider): void
    {
        $user = new User();
        $user->setUserId($userDataProvider->getUserId());
        $user->delete();
    }

    /**
     * @param \Orm\Xervice\User\Persistence\User $user
     * @param \DataProvider\UserDataProvider $userDataProvider
     *
     * @return \Orm\Xervice\User\Persistence\User
     */
    private function hydrateEntityFromDataProvider(User $user, UserDataProvider $userDataProvider): User
    {
        $user->fromArray($userDataProvider->toArray());

        foreach ($userDataProvider->getUserLogins() as $loginDataProvider) {
            $userLoginEntity = $this->getUserLoginEntity($loginDataProvider);
            $user->addUserLogin($userLoginEntity);
        }

        return $user;
    }

    /**
     * @param $loginDataProvider
     *
     * @return \Orm\Xervice\User\Persistence\UserLogin
     */
    private function getUserLoginEntity($loginDataProvider): \Orm\Xervice\User\Persistence\UserLogin
    {
        $userLoginEntity = new UserLogin();
        $userLoginEntity->fromArray($loginDataProvider->toArray());

        foreach ($loginDataProvider->getUserCredentials() as $credentialDataProvider) {
            $userCredentialEntity = $this->getUserCredentialsEntity($credentialDataProvider);
            $userLoginEntity->addUserCredential($userCredentialEntity);
        }
        return $userLoginEntity;
    }

    /**
     * @param $credentialDataProvider
     *
     * @return \Orm\Xervice\User\Persistence\UserCredential
     */
    private function getUserCredentialsEntity($credentialDataProvider): \Orm\Xervice\User\Persistence\UserCredential
    {
        $userCredentialEntity = new UserCredential();
        $userCredentialEntity->fromArray($credentialDataProvider->toArray());
        return $userCredentialEntity;
    }
}