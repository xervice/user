<?php


namespace Xervice\User\Business\Model\Authenticator;


use DataProvider\UserAuthDataProvider;
use Xervice\User\Business\Exception\UserException;

class AuthProvider implements AuthProviderInterface
{
    /**
     * @var \Xervice\User\Business\Model\Authenticator\UserCredentialProviderInterface
     */
    private $credentialProvider;

    /**
     * @var \Xervice\User\Business\Dependency\Authenticator\Login\LoginInterface[]
     */
    private $loginCollection;

    /**
     * AuthProvider constructor.
     *
     * @param \Xervice\User\Business\Model\Authenticator\UserCredentialProviderInterface $credentialProvider
     * @param \Xervice\User\Business\Dependency\Authenticator\Login\LoginInterface[] $loginCollection
     */
    public function __construct(
        UserCredentialProviderInterface $credentialProvider,
        array $loginCollection
    ) {
        $this->credentialProvider = $credentialProvider;
        $this->loginCollection = $loginCollection;
    }

    /**
     * @param \DataProvider\UserAuthDataProvider $authDataProvider
     *
     * @return bool
     * @throws \Xervice\User\Business\Exception\UserException
     */
    public function auth(UserAuthDataProvider $authDataProvider): bool
    {
        $login = $this->loginCollection[$authDataProvider->getType()] ?? null;

        if (!$login) {
            throw new UserException(
                sprintf(
                    'Login type %s not found',
                    $authDataProvider->getType()
                )
            );
        }

        return $login->auth(
            $authDataProvider,
            $this->credentialProvider->getCredentialsForType(
                $authDataProvider->getUser(),
                $authDataProvider->getType()
            )
        );
    }
}