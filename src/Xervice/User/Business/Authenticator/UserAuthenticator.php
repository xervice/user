<?php


namespace Xervice\User\Business\Authenticator;


use DataProvider\UserDataProvider;
use DataProvider\UserLoginDataProvider;
use Xervice\User\Business\Exception\UserException;
use Xervice\User\UserQueryContainerInterface;

class UserAuthenticator implements UserAuthenticatorInterface
{
    /**
     * @var \Xervice\User\UserQueryContainerInterface
     */
    private $queryContainer;

    /**
     * UserAuthenticator constructor.
     *
     * @param \Xervice\User\UserQueryContainerInterface $queryContainer
     */
    public function __construct(UserQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param string $email
     * @param string $type
     * @param string $hash
     *
     * @return bool
     * @throws \Xervice\User\Business\Exception\UserException
     */
    public function authenticate(string $email, string $type, string $hash): bool
    {
        $user = $this->queryContainer->getUserFromEmail($email);
        if (!$user->hasUserId()) {
            $this->throwException(
                'User %s not found',
                $email
            );
        }

        return $this->authLogins($user, $type, $hash);
    }

    /**
     * @param \DataProvider\UserDataProvider $userDataProvider
     * @param string $type
     * @param string $hash
     *
     * @return bool
     * @throws \Xervice\User\Business\Exception\UserException
     */
    private function authLogins(UserDataProvider $userDataProvider, string $type, string $hash): bool
    {
        foreach ($userDataProvider->getUserLogins() as $login) {
            if ($login->getType() === $type) {
                return $this->authCredentials($login, $hash);
            }
        }

        $this->throwException('No valid login for type %s', $type);
    }

    /**
     * @param \DataProvider\UserLoginDataProvider $loginDataProvider
     * @param string $hash
     *
     * @return bool
     * @throws \Xervice\User\Business\Exception\UserException
     */
    private function authCredentials(UserLoginDataProvider $loginDataProvider, string $hash): bool
    {
        foreach ($loginDataProvider->getUserCredentials() as $credential) {
            if ($credential->getHash() === $hash) {
                return true;
            }
        }

        $this->throwException('No valid credentials');
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