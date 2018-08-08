<?php


namespace Xervice\User\Business\Authenticator;


use DataProvider\UserCredentialDataProvider;
use DataProvider\UserDataProvider;
use Xervice\User\Business\Exception\UserException;
use Xervice\User\UserQueryContainerInterface;

class UserCredentialProvider implements UserCredentialProviderInterface
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
     * @param \DataProvider\UserDataProvider $userDataProvider
     * @param string $type
     *
     * @return \DataProvider\UserCredentialDataProvider
     * @throws \Xervice\User\Business\Exception\UserException
     */
    public function getCredentialsForType(UserDataProvider $userDataProvider, string $type): UserCredentialDataProvider
    {
        $user = $this->queryContainer->getUserFromEmail($userDataProvider->getEmail());
        if (!$user->hasUserId()) {
            $this->throwException(
                'User %s not found',
                $userDataProvider->getEmail()
            );
        }

        return $this->authLogins($user, $type);
    }

    /**
     * @param \DataProvider\UserDataProvider $userDataProvider
     * @param string $type
     *
     * @return \DataProvider\UserCredentialDataProvider
     * @throws \Xervice\User\Business\Exception\UserException
     */
    private function authLogins(UserDataProvider $userDataProvider, string $type): UserCredentialDataProvider
    {
        foreach ($userDataProvider->getUserLogins() as $login) {
            if ($login->getType() === $type) {
                return $login->getUserCredentials();
            }
        }

        $this->throwException('No valid login for type %s', $type);
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