<?php
namespace XerviceTest\User;

use DataProvider\UserCredentialDataProvider;
use DataProvider\UserDataProvider;
use DataProvider\UserLoginDataProvider;
use Orm\Xervice\User\Persistence\User;
use Orm\Xervice\User\Persistence\UserCredential;
use Orm\Xervice\User\Persistence\UserLogin;
use Orm\Xervice\User\Persistence\UserQuery;
use Propel\Runtime\Map\TableMap;
use Xervice\Core\Locator\Dynamic\DynamicLocator;
use Xervice\Core\Locator\Locator;
use Xervice\Database\DatabaseFacade;

/**
 * @method \Xervice\User\UserFacade getFacade()
 * @method \Xervice\User\UserFactory getFactory()
 */
class IntegrationTest extends \Codeception\Test\Unit
{
    use DynamicLocator;

    protected function _before()
    {
        $this->getDatabaseFacade()->generateConfig();
        $this->getDatabaseFacade()->buildModel();
        $this->getDatabaseFacade()->migrate();
        $this->getDatabaseFacade()->initDatabase();
    }

    /**
     * @group Xervice
     * @group User
     * @group Integration
*/
    public function testCreateUser()
    {
        $credential = new UserCredentialDataProvider();
        $credential
            ->setHash('myHash');

        $login = new UserLoginDataProvider();
        $login
            ->setType('test')
            ->addUserCredential($credential);

        $user = new UserDataProvider();
        $user
            ->setEmail('test@test.de')
            ->addUserLogin($login);

        $user = $this->getFacade()->createUser($user);

        $loginTwo = new UserLoginDataProvider();
        $loginTwo
            ->setType('second');

        $this->getFacade()->addUserLogin($user, $loginTwo);

        $userFromDb = $this->getFacade()->getUserFromEmail('test@test.de');

        $this->assertEquals(
            $userFromDb->getEmail(),
            $user->getEmail()
        );

        $this->assertEquals(
            'test',
            $userFromDb->getUserLogins()[0]->getType()
        );

        $this->assertEquals(
            'second',
            $userFromDb->getUserLogins()[1]->getType()
        );

        $this->assertEquals(
            $userFromDb->getUserLogins()[0]->getUserCredentials()[0]->getHash(),
            $user->getUserLogins()[0]->getUserCredentials()[0]->getHash()
        );

        $this->getFacade()->deleteUser($userFromDb);
    }

    /**
     * @return \Xervice\Database\DatabaseFacade
     */
    private function getDatabaseFacade(): DatabaseFacade
    {
        return Locator::getInstance()->database()->facade();
    }
}