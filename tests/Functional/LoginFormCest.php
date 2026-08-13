<?php

declare(strict_types=1);

namespace app\tests\Functional;

use app\controllers\SiteController;
use app\tests\Support\FunctionalTester;

final class LoginFormCest {

    public function _fixtures(): array {
        return [
            'user' => \app\tests\Unit\fixtures\UserFixture::class,
        ];
    }

    public function _before(FunctionalTester $I) {
        $I->amOnRoute('site/login');
    }

    public function openLoginPage(FunctionalTester $I) {
        $I->see('Login', 'h1');
    }

    // demonstrates `amLoggedInAs` method
    public function internalLoginById(FunctionalTester $I) {
        $I->amLoggedInAs(env('TESTING_USER_ID'));
        $I->amOnPage('/');
        $I->see('Sign Out');
    }

    // demonstrates `amLoggedInAs` method
    public function internalLoginByInstance(FunctionalTester $I) {
        $I->amLoggedInAs(\app\models\User::findByUsername(env('TESTING_USERNAME')));
        $I->amOnPage('/');
        $I->see('Sign Out');
    }

    public function loginWithEmptyCredentials(FunctionalTester $I) {
        $I->submitForm('#login-form', []);
        $I->expectTo('see validations errors');
        $I->see('Username cannot be blank.');
        $I->see('Password cannot be blank.');
    }

    public function loginWithWrongCredentials(FunctionalTester $I) {
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'admin',
            'LoginForm[password]' => 'wrong',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Incorrect username or password.');
    }

    /**
     * Automates the login process by submitting the login form
     * with predefined test credentials and verifies that the user
     * is logged in successfully.
     *
     * @param FunctionalTester $I The tester instance used to interact with the application.
     * @see SiteController::actionLogin()
     * @return void
     */
    public function loginSuccessfully(FunctionalTester $I): void {
        $I->submitForm('#login-form', [
            'LoginForm[username]' => env('TESTING_USERNAME'),
            'LoginForm[password]' => env('TESTING_PASSWORD'),
        ]);
        $I->see('Sign Out');
        $I->dontSeeElement('form#login-form');
    }
}
