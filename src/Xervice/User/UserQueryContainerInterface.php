<?php

namespace Xervice\User;

use DataProvider\UserDataProvider;
use Orm\Xervice\User\Persistence\UserQuery;

interface UserQueryContainerInterface
{
    /**
     * @return \Orm\Xervice\User\Persistence\UserQuery
     */
    public function getUserQuery(): UserQuery;

    /**
     * @param int $userId
     *
     * @return UserDataProvider
     */
    public function getUserFromId(int $userId): UserDataProvider;

    /**
     * @param string $email
     *
     * @return UserDataProvider
     */
    public function getUserFromEmail(string $email): UserDataProvider;

    /**
     * @param int $userId
     *
     * @return $this|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function getUserQueryFromUserId(int $userId);

    /**
     * @param string $email
     *
     * @return $this|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function getUserQueryFromEmail(string $email);
}