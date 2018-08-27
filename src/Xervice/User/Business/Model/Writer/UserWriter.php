<?php


namespace Xervice\User\Business\Model\Writer;


use DataProvider\UserDataProvider;
use DataProvider\UserLoginDataProvider;
use Orm\Xervice\User\Persistence\User;
use Orm\Xervice\User\Persistence\UserCredential;
use Orm\Xervice\User\Persistence\UserLogin;
use Xervice\User\Business\Model\Exception\UserException;
use Xervice\User\Business\Model\Validator\UserValidatorInterface;
use Xervice\User\Persistence\UserDataReader;
use Xervice\User\UserQueryContainerInterface;

class UserWriter implements UserWriterInterface
{
    /**
     * @var \Xervice\User\Business\Model\Validator\UserValidatorInterface
     */
    private $validator;

    /**
     * @var \Xervice\User\Persistence\UserDataReader
     */
    private $userDataReader;

    /**
     * UserWriter constructor.
     *
     * @param \Xervice\User\Business\Model\Validator\UserValidatorInterface $validator
     * @param \Xervice\User\Persistence\UserDataReader $userDataReader
     */
    public function __construct(
        UserValidatorInterface $validator,
        UserDataReader $userDataReader
    ) {
        $this->validator = $validator;
        $this->userDataReader = $userDataReader;
    }

    /**
     * @param \DataProvider\UserDataProvider $userDataProvider
     *
     * @return \DataProvider\UserDataProvider
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Xervice\User\Business\Model\Exception\UserException
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
     * @throws \Xervice\User\Business\Model\Exception\UserException
     */
    public function updateUser(UserDataProvider $userDataProvider): UserDataProvider
    {
        $this->validator->validateUser($userDataProvider);

        $user = $this->userDataReader->getUserQuery()->findOneByUserId($userDataProvider->getUserId());
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
                $userLoginEntity = $this->userDataReader->getLoginEntityFromDataProvider($loginDataProvider);
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