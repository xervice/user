<?php

namespace Xervice\User\Business\Writer;

use DataProvider\UserCredentialDataProvider;

interface CredentialWriterInterface
{
    /**
     * @param \DataProvider\UserCredentialDataProvider $userCredentialDataProvider
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function updateCredential(UserCredentialDataProvider $userCredentialDataProvider): void;
}