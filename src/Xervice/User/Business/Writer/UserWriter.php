<?php


namespace Xervice\User\Business\Writer;


use DataProvider\UserDataProvider;
use Orm\Xervice\User\Persistence\User;
use Orm\Xervice\User\Persistence\UserCredential;
use Orm\Xervice\User\Persistence\UserLogin;
use Xervice\User\Business\Validator\UserValidatorInterface;

class UserWriter implements UserWriterInterface
{
    /**
     * @var \Xervice\User\Business\Validator\UserValidatorInterface
     */
    private $validator;

    /**
     * UserWriter constructor.
     *
     * @param \Xervice\User\Business\Validator\UserValidatorInterface $validator
     */
    public function __construct(UserValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param \DataProvider\UserDataProvider $userDataProvider
     *
     * @return \DataProvider\UserDataProvider
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Xervice\User\Business\Exception\UserException
     */
    public function createUser(UserDataProvider $userDataProvider)
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
            $userLoginEntity = new UserLogin();
            $userLoginEntity->fromArray($loginDataProvider->toArray());

            foreach ($loginDataProvider->getUserCredentials() as $credentialDataProvider) {
                $userCredentialEntity = new UserCredential();
                $userCredentialEntity->fromArray($credentialDataProvider->toArray());
                $userLoginEntity->addUserCredential($userCredentialEntity);
            }

            $user->addUserLogin($userLoginEntity);
        }

        return $user;
    }
}