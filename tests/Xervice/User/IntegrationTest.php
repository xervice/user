<?php
namespace XerviceTest\User;

use DataProvider\UserAuthDataProvider;
use DataProvider\UserCredentialDataProvider;
use DataProvider\UserDataProvider;
use DataProvider\UserLoginDataProvider;
use Xervice\Core\Business\Model\Locator\Dynamic\Business\DynamicBusinessLocator;
use Xervice\Core\Business\Model\Locator\Locator;
use Xervice\Database\Business\DatabaseFacade;

/**
 * @method \Xervice\User\Business\UserFacade getFacade()
 * @method \Xervice\User\Business\UserBusinessFactory getFactory()
 */
class IntegrationTest extends \Codeception\Test\Unit
{
    use DynamicBusinessLocator;

    protected function _before()
    {
        $this->getDatabaseFacade()->generateConfig();
        $this->getDatabaseFacade()->buildModel();
        $this->getDatabaseFacade()->migrate();
        $this->getDatabaseFacade()->initDatabase();

        $this->createTestUser();
    }

    protected function _after()
    {
        $userFromDb = $this->getFacade()->getUserFromEmail('test@test.de');
        if ($userFromDb) {
            $this->getFacade()->deleteUser($userFromDb);
        }
    }


    /**
     * @group Xervice
     * @group User
     * @group Integration
     */
    public function testCreateUser()
    {
        $userFromDb = $this->getFacade()->getUserFromEmail('test@test.de');

        $this->assertEquals(
            'test@test.de',
            $userFromDb->getEmail()
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
            'myHash',
            $userFromDb->getUserLogins()[0]->getUserCredential()->getHash()
        );
    }

    public function testUpdateUser()
    {
        $userFromDb = $this->getFacade()->getUserFromEmail('test@test.de');
        $userFromDb->setEmail('test2@test.de');
        $userFromDb->unsetUserLogins();

        $loginThree = new UserLoginDataProvider();
        $loginThree
            ->setType('three')
            ->setUserCredential((new UserCredentialDataProvider())->setHash('ThirdHash'));

        $userFromDb->addUserLogin($loginThree);

        $this->getFacade()->updateUser($userFromDb);

        $userFromDb = $this->getFacade()->getUserFromEmail('test2@test.de');

        $this->assertEquals(
            'test2@test.de',
            $userFromDb->getEmail()
        );

        $this->assertEquals(
            'three',
            $userFromDb->getUserLogins()[2]->getType()
        );

        $this->getFacade()->deleteUser($userFromDb);
    }

    public function testUpdateLogin()
    {
        $userFromDb = $this->getFacade()->getUserFromEmail('test@test.de');

        $login = $userFromDb->getUserLogins()[0];
        $login->setType('NEWTYPE');

        $this->getFacade()->updateLogin($login);

        $userTest = $this->getFacade()->getUserFromEmail('test@test.de');

        $this->assertEquals(
            'NEWTYPE',
            $userTest->getUserLogins()[0]->getType()
        );

        $this->getFacade()->deleteUser($userTest);
    }

    public function testGetUserLogin()
    {
        $userFromDb = $this->getFacade()->getUserFromEmail('test@test.de');

        $login = $this->getFacade()->getLoginFromUserByType($userFromDb->getUserId(), 'second');

        $this->assertEquals(
            'second',
            $login->getType()
        );
    }

    public function testGetUserLoginNotExist()
    {
        $userFromDb = $this->getFacade()->getUserFromEmail('test@test.de');

        $login = $this->getFacade()->getLoginFromUserByType($userFromDb->getUserId(), 'ola');

        $this->assertEquals(
            'Default',
            $login->getType()
        );
    }

    public function testUpdateCredentials()
    {
        $userFromDb = $this->getFacade()->getUserFromEmail('test@test.de');

        $credential = $userFromDb->getUserLogins()[0]->getUserCredential();
        $credential->setHash('NEWHASH');

        $this->getFacade()->updateCredential($credential);

        $userTest = $this->getFacade()->getUserFromEmail('test@test.de');

        $this->assertEquals(
            'NEWHASH',
            $userTest->getUserLogins()[0]->getUserCredential()->getHash()
        );

        $this->getFacade()->deleteUser($userTest);
    }

    /**
     * @expectedException \Xervice\User\Business\Exception\UserException
     * @expectedExceptionMessage Login type Default not found
     */
    public function testLoginWithoutTypes()
    {
        $auth = new UserAuthDataProvider();
        $auth
            ->setType('Default')
            ->setUser((new UserDataProvider())->setEmail('test@test.de'))
            ->setCredential((new UserCredentialDataProvider())->setHash('hash123'));

        $this->getFacade()->auth($auth);
    }

    private function createTestUser(): void
    {
        $login = new UserLoginDataProvider();
        $login
            ->setType('test')
            ->setUserCredential((new UserCredentialDataProvider())->setHash('myHash'));

        $loginTwo = new UserLoginDataProvider();
        $loginTwo
            ->setType('second')
            ->setUserCredential((new UserCredentialDataProvider())->setHash('SecondHash'));

        $user = new UserDataProvider();
        $user
            ->setEmail('test@test.de')
            ->addUserLogin($login)
            ->addUserLogin($loginTwo);

        $this->getFacade()->createUser($user);
    }

    /**
     * @return \Xervice\Database\Business\DatabaseFacade
     */
    private function getDatabaseFacade(): DatabaseFacade
    {
        return Locator::getInstance()->database()->facade();
    }
}