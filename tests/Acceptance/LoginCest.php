<?php

declare(strict_types=1);

namespace app\tests\Acceptance;

use app\tests\Support\AcceptanceTester;
use yii\helpers\Url;

final class LoginCest
{
    public function ensureThatLoginWorks(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/login'));
        $I->see('Login', 'h1');

        $I->amGoingTo('try to login with correct credentials');
        $I->fillField('input[name="LoginForm[username]"]', env('TESTING_USERNAME'));
        $I->fillField('input[name="LoginForm[password]"]', env('TESTING_PASSWORD'));
        $I->click('login-button');

        $I->waitForElementVisible('.site-index', 10);
        $I->expectTo('see Dashboard');
        $I->see('Sign Out');
    }
}
