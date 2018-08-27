<?php


namespace Xervice\User\Business\Model\Login;


use DataProvider\UserAuthDataProvider;
use DataProvider\UserDataProvider;
use Xervice\Session\Business\SessionFacade;
use Xervice\User\Business\Exception\UserException;
use Xervice\User\Business\Model\Authenticator\AuthProviderInterface;

class LoginHandler implements LoginHandlerInterface
{
    private const SESSION_USER_NAME = 'session:user:data';

    /**
     * @var \Xervice\Session\Business\SessionFacade
     */
    private $sessionFacade;

    /**
     * @var \Xervice\User\Business\Model\Authenticator\AuthProviderInterface
     */
    private $authProvider;

    /**
     * LoginHandler constructor.
     *
     * @param \Xervice\Session\Business\SessionFacade $sessionFacade
     * @param \Xervice\User\Business\Model\Authenticator\AuthProviderInterface $authProvider
     */
    public function __construct(
        SessionFacade $sessionFacade,
        AuthProviderInterface $authProvider
    ) {
        $this->sessionFacade = $sessionFacade;
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

        $this->sessionFacade->set(
            self::SESSION_USER_NAME,
            json_encode(
                $authDataProvider->getUser()->toArray()
            )
        );
        return $authDataProvider->getUser();
    }

    public function logout(): void
    {
        $this->sessionFacade->remove(self::SESSION_USER_NAME);
    }

    /**
     * @return \DataProvider\UserDataProvider|null
     */
    public function getUser(): ?UserDataProvider
    {
        $user = null;
        if ($this->sessionFacade->has(self::SESSION_USER_NAME)) {
            $user = new UserDataProvider();
            $user->fromArray(
                json_decode(
                    $this->sessionFacade->get(self::SESSION_USER_NAME),
                    true
                )
            );
        }

        return $user;
    }
}