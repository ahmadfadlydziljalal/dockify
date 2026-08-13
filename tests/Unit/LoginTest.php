<?php

declare(strict_types=1);

namespace app\tests\Unit;

use app\controllers\SiteController;
use app\models\User;
use Yii;
use yii\base\Security;
use yii\web\View;

final class LoginTest extends \Codeception\Test\Unit
{

    // di LoginTest / LogoutTest / UserTest
    public function _fixtures(): array
    {
        return [
            'user' => \app\tests\Unit\fixtures\UserFixture::class,
        ];
    }

    public function testRenderLoginWrongUsername(): void
    {
        $controller = new SiteController(
            'site',
            Yii::$app,
            Yii::$app->mailer,
            new Security(),
            new \app\components\SihrdAuthClient(),
        );

        $view = new View(['context' => $controller]);

        Yii::$app->user->login(new User());

        $controller->actionLogin();

        self::assertStringContainsString(
            'Guest',
            $view->render('//layouts/main.php', ['content' => 'Hello World°']),
            'Failed asserting that the guest state is rendered for a wrong username.',
        );
    }
}
