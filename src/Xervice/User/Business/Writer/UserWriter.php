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
        return $this->persistUserFromDataProvider($userDataProvider, $user);
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
        $this->validator->validateUser($userDataProvider);

        $user = $this->queryContainer->getUserQuery()->findOneByUserId($userDataProvider->getUserId());
        return $this->persistUserFromDataProvider($userDataProvider, $user);
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
     * @throws \Propel\Runtime\Exception\PropelException
     */
    private function hydrateEntityFromDataProvider(User $user, UserDataProvider $userDataProvider): User
    {
        $user->fromArray($userDataProvider->toArray());

        if ($userDataProvider->hasUserLogins()) {
            foreach ($userDataProvider->getUserLogins() as $loginDataProvider) {
                $userLoginEntity = $this->queryContainer->getLoginEntityFromDataProvider($loginDataProvider);
                $user->addUserLogin($userLoginEntity);
            }
        }

        return $user;
    }

    /**
     * @param \DataProvider\UserDataProvider $userDataProvider
     * @param $user
     *
     * @return \DataProvider\UserDataProvider
     * @throws \Propel\Runtime\Exception\PropelException
     */
    private function persistUserFromDataProvider(
        UserDataProvider $userDataProvider,
        User $user
    ): \DataProvider\UserDataProvider {

        $user = $this->hydrateEntityFromDataProvider($user, $userDataProvider);
        $user->save();

        return $userDataProvider->setUserId($user->getUserId());
    }
}