<?php


namespace Xervice\User\Business\Model\Writer;


use DataProvider\UserLoginDataProvider;
use Xervice\User\Persistence\UserDataReader;
use Xervice\User\UserQueryContainerInterface;

class LoginWriter implements LoginWriterInterface
{
    /**
     * @var \Xervice\User\Persistence\UserDataReader
     */
    private $userDataReader;

    /**
     * LoginWriter constructor.
     *
     * @param \Xervice\User\Persistence\UserDataReader $userDataReader
     */
    public function __construct(UserDataReader $userDataReader)
    {
        $this->userDataReader = $userDataReader;
    }

    /**
     * @param \DataProvider\UserLoginDataProvider $loginDataProvider
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function updateLogin(UserLoginDataProvider $loginDataProvider): void
    {
        $login = $this->userDataReader->getLoginEntityFromDataProvider($loginDataProvider);
        $login->save();
    }
}