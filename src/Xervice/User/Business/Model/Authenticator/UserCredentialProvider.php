<?php


namespace Xervice\User\Business\Model\Authenticator;


use DataProvider\UserCredentialDataProvider;
use DataProvider\UserDataProvider;
use Xervice\User\Business\Exception\UserException;
use Xervice\User\Persistence\UserDataReader;

class UserCredentialProvider implements UserCredentialProviderInterface
{
    /**
     * @var \Xervice\User\Persistence\UserDataReader
     */
    private $userReader;

    /**
     * UserAuthenticator constructor.
     *
     * @param \Xervice\User\Persistence\UserDataReader $userReader
     */
    public function __construct(UserDataReader $userReader)
    {
        $this->userReader = $userReader;
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
        $user = $this->userReader->getUserFromEmail($userDataProvider->getEmail());
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
                return $login->getUserCredential();
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