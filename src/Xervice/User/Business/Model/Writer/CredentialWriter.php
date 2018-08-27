<?php


namespace Xervice\User\Business\Model\Writer;


use DataProvider\UserCredentialDataProvider;
use Xervice\User\Persistence\UserDataReader;
use Xervice\User\UserQueryContainerInterface;

class CredentialWriter implements CredentialWriterInterface
{
    /**
     * @var \Xervice\User\Persistence\UserDataReader
     */
    private $userDataReader;

    /**
     * CredentialWriter constructor.
     *
     * @param \Xervice\User\Persistence\UserDataReader $userDataReader
     */
    public function __construct(UserDataReader $userDataReader)
    {
        $this->userDataReader = $userDataReader;
    }

    /**
     * @param \DataProvider\UserCredentialDataProvider $userCredentialDataProvider
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function updateCredential(UserCredentialDataProvider $userCredentialDataProvider): void
    {
        $credential = $this->userDataReader->getUserCredentialsEntity($userCredentialDataProvider);
        $credential->save();
    }


}