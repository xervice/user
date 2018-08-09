<?php


namespace Xervice\User\Business\Writer;


use DataProvider\UserCredentialDataProvider;
use Xervice\User\UserQueryContainerInterface;

class CredentialWriter implements CredentialWriterInterface
{
    /**
     * @var \Xervice\User\UserQueryContainerInterface
     */
    private $queryContainer;

    /**
     * CredentialWriter constructor.
     *
     * @param \Xervice\User\UserQueryContainerInterface $queryContainer
     */
    public function __construct(UserQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \DataProvider\UserCredentialDataProvider $userCredentialDataProvider
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function updateCredential(UserCredentialDataProvider $userCredentialDataProvider): void
    {
        $credential = $this->queryContainer->getUserCredentialsEntity($userCredentialDataProvider);
        $credential->save();
    }


}