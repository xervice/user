<?php


namespace Xervice\User\Business\Validator;


use DataProvider\UserDataProvider;
use Xervice\User\Business\Exception\UserException;

class UserValidator implements UserValidatorInterface
{
    /**
     * @param \DataProvider\UserDataProvider $userDataProvider
     *
     * @throws \Xervice\User\Business\Exception\UserException
     */
    public function validateUser(UserDataProvider $userDataProvider)
    {
        if (!$userDataProvider->hasEmail()) {
            $this->throwException(
                'User has no email'
            );
        }
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