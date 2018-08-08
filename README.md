Xervice: User
=====================

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/xervice/user/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/xervice/user/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/xervice/user/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/xervice/user/?branch=master)


Installation
-----------------
```
composer require xervice/user
```

Configuration
-----------------
You must add LoginTypes to the UserDependencyProvider.

```php
<?php
declare(strict_types=1);


namespace App\User;


class UserDependencyProvider extends XerviceUserDependencyProvider
{
    /**
     * type => {Login::class}
     *
     * @return \Xervice\User\Business\Authenticator\Login\LoginInterface[]
     */
    protected function getLoginPluginList(): array
    {
        return [
            'Default' => new DefaultLogin()
        ];
    }
}
```


Using
-----------------
```
// ---- Create User ------
$login = new UserLoginDataProvider();
$login
    ->setType('Default')
    ->setUserCredential(
        (new UserCredentialDataProvider())
            ->setHash(
                password_hash('myHash', PASSWORD_BCRYPT)
            )
    );

$user = new UserDataProvider();
$user
    ->setEmail('test@test.de')
    ->addUserLogin($login);


// ------ Auth User DataProvider ------
$auth = new UserAuthDataProvider();
$auth
    ->setType('Default')
    ->setUser((new UserDataProvider())->setEmail('test@test.de'))
    ->setCredential((new UserCredentialDataProvider())->setHash('myHash'));


// ------ Auth User ------

$userFacade->auth($auth); // return true


$auth = new UserAuthDataProvider();
$auth
    ->setType('WrongType')
    ->setUser((new UserDataProvider())->setEmail('test@test.de'))
    ->setCredential((new UserCredentialDataProvider())->setHash('wrongHash'));

$userFacade->auth($auth); // return false

$auth = new UserAuthDataProvider();
$auth
    ->setType('WrongType')
    ->setUser((new UserDataProvider())->setEmail('test@test.de'))
    ->setCredential((new UserCredentialDataProvider())->setHash('myHash'));

$userFacade->auth($auth); // throw UserException

// ------ Login User ------
$userFacade->login($auth); // return UserDataProvider or throw UserException

$userFacade->logout(); // remove user from session

$userFacade->getUser(); // Get active user or null
```