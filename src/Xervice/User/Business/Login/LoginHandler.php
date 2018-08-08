<?php


namespace Xervice\User\Business\Login;


use DataProvider\UserAuthDataProvider;
use DataProvider\UserDataProvider;
use Xervice\Session\SessionClient;
use Xervice\User\Business\Authenticator\AuthProviderInterface;
use Xervice\User\Business\Exception\UserException;

class LoginHandler implements LoginHandlerInterface
{
    private const SESSION_USER_NAME = 'session:user:data';

    /**
     * @var \Xervice\Session\SessionClient
     */
    private $sessionClient;

    /**
     * @var \Xervice\User\Business\Authenticator\AuthProviderInterface
     */
    private $authProvider;

    /**
     * LoginHandler constructor.
     *
     * @param \Xervice\Session\SessionClient $sessionClient
     * @param \Xervice\User\Business\Authenticator\AuthProviderInterface $authProvider
     */
    public function __construct(
        SessionClient $sessionClient,
        AuthProviderInterface $authProvider
    ) {
        $this->sessionClient = $sessionClient;
        $this->authProvider = $authProvider;
    }

    /**
     * @param \DataProvider\UserAuthDataProvider $authDataProvider
     *
     * @return \DataProvider\UserDataProvider
     * @throws \Xervice\User\Business\Exception\UserException
     */
    public function login(UserAuthDataProvider $authDataProvider)
    {
        if (!$this->authProvider->auth($authDataProvider)) {
            throw new UserException('Login failed');
        }

        $this->sessionClient->set(
            self::SESSION_USER_NAME,
            json_encode(
                $authDataProvider->getUser()->toArray()
            )
        );
        return $authDataProvider->getUser();
    }

    public function logout(): void
    {
        $this->sessionClient->remove(self::SESSION_USER_NAME);
    }

    /**
     * @return \DataProvider\UserDataProvider|null
     */
    public function getUser(): ?UserDataProvider
    {
        $user = null;
        if ($this->sessionClient->has(self::SESSION_USER_NAME)) {
            $user = new UserDataProvider();
            $user->fromArray(
                json_decode(
                    $this->sessionClient->get(self::SESSION_USER_NAME),
                    true
                )
            );
        }

        return $user;
    }
}