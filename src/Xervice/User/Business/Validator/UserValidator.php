<?php


namespace Xervice\User\Business\Validator;


use DataProvider\UserDataProvider;
use Xervice\User\Business\Exception\UserException;
use Xervice\User\UserQueryContainerInterface;

class UserValidator implements UserValidatorInterface
{
    /**
     * @var \Xervice\User\UserQueryContainerInterface
     */
    private $queryContainer;

    /**
     * UserValidator constructor.
     *
     * @param \Xervice\User\UserQueryContainerInterface $queryContainer
     */
    public function __construct(UserQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \DataProvider\UserDataProvider $userDataProvider
     *
     * @throws \Xervice\User\Business\Exception\UserException
     */
    public function validateUser(UserDataProvider $userDataProvider)
    {
        $this->userHasEmail($userDataProvider);
        $this->validateLoginHasCredential($userDataProvider);
        $this->validateWithDb(
            $userDataProvider,
            $this->queryContainer->getUserFromEmail($userDataProvider->getEmail())
        );
    }

    /**
     * @param \DataProvider\UserDataProvider $userDataProvider
     * @param \DataProvider\UserDataProvider $dbUser
     *
     * @throws \Xervice\User\Business\Exception\UserException
     */
    protected function validateWithDb(UserDataProvider $userDataProvider, UserDataProvider $dbUser): void
    {
        $this->userExist($userDataProvider, $dbUser);
        $this->userLoginExist($userDataProvider, $dbUser);
    }

    /**
     * @param \DataProvider\UserDataProvider $userDataProvider
     *
     * @throws \Xervice\User\Business\Exception\UserException
     */
    private function validateLoginHasCredential(UserDataProvider $userDataProvider): void
    {
        foreach ($userDataProvider->getUserLogins() as $login) {
            if (!$login->hasUserCredential()) {
                $this->throwException('Login %s has no credentials', $login->getType());
            }
        }
    }

    /**
     * @param \DataProvider\UserDataProvider $userDataProvider
     * @param \DataProvider\UserDataProvider $dbUser
     *
     * @throws \Xervice\User\Business\Exception\UserException
     */
    private function userLoginExist(UserDataProvider $userDataProvider, UserDataProvider $dbUser): void
    {
        if ($userDataProvider->hasUserLogins()) {
            foreach ($userDataProvider->getUserLogins() as $login) {
                foreach ($dbUser->getUserLogins() as $dbLogin) {
                    if (
                        $dbLogin->getType() === $login->getType()
                        && $dbLogin->getUserLoginId() !== $login->getUserLoginId()
                    ) {
                        $this->throwException(
                            'Login type %s already exist for this user',
                            $login->getType()
                        );
                    }
                }
            }
        }
    }

    /**
     * @param \DataProvider\UserDataProvider $userDataProvider
     * @param \DataProvider\UserDataProvider $dbUser
     *
     * @throws \Xervice\User\Business\Exception\UserException
     */
    private function userExist(UserDataProvider $userDataProvider, UserDataProvider $dbUser): void
    {
        if (
            $dbUser->hasUserId()
            && $dbUser->getUserId() !== $userDataProvider->getUserId()
        ) {
            $this->throwException('User already exists');
        }
    }

    /**
     * @throws \Xervice\User\Business\Exception\UserException
     */
    private function userHasEmail(UserDataProvider $userDataProvider): void
    {
        if (!$userDataProvider->hasEmail()) {
            $this->throwException(
                'User has no email'
            );
        }
    }

    /**
     * @param $message
     * @param mixed ...$params
     *
     * @throws \Xervice\User\Business\Exception\UserException
     */
    private function throwException($message, ...$params): void
    {
        throw new UserException(
            sprintf(
                $message,
                ...$params
            )
        );
    }
}