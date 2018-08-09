<?php


namespace Xervice\User\Business\Writer;


use DataProvider\UserLoginDataProvider;
use Xervice\User\UserQueryContainerInterface;

class LoginWriter implements LoginWriterInterface
{
    /**
     * @var \Xervice\User\UserQueryContainerInterface
     */
    private $queryContainer;

    /**
     * LoginWriter constructor.
     *
     * @param \Xervice\User\UserQueryContainerInterface $queryContainer
     */
    public function __construct(UserQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \DataProvider\UserLoginDataProvider $loginDataProvider
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function updateLogin(UserLoginDataProvider $loginDataProvider): void
    {
        $login = $this->queryContainer->getLoginEntityFromDataProvider($loginDataProvider);
        $login->save();
    }
}