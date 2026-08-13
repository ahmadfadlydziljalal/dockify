<?php

declare(strict_types=1);

namespace app\tests\Unit;

use app\controllers\SiteController;
use app\models\User;
use Yii;
use yii\base\Security;
use yii\web\IdentityInterface;
use yii\web\View;

final class LogoutTest extends \Codeception\Test\Unit
{

    public function _fixtures(): array
    {
        return [
            'user' => \app\tests\Unit\fixtures\UserFixture::class,
        ];
    }

    public function testRenderLogoutLinkWhenUserIsLoggedIn(): void
    {
        $user = User::findByUsername(env('TESTING_USERNAME'));

        $controller = new SiteController(
            'site',
            Yii::$app,
            Yii::$app->mailer,
            new Security(),
            new \app\components\SihrdAuthClient(),
        );

        $view = new View(['context' => $controller]);

        self::assertNotNull(
            $user,
            "Failed asserting that the fixture user with username '" . env('TESTING_USERNAME') . "' exists.",
        );
        self::assertInstanceOf(
            IdentityInterface::class,
            $user,
            "Failed asserting that the identity is an instance of 'Identity' class.",
        );

        Yii::$app->user->login($user);

        $html = $view->render('//layouts/main.php', ['content' => 'Hello World°']);

        self::assertStringContainsString(
            '3698-PS-22071989',
            $html,
            'Failed asserting that the logged-in username is rendered.',
        );
        self::assertStringContainsString(
            'data-method="post"',
            $html,
            'Failed asserting that the logout link uses POST method.',
        );

        $controller->actionLogout();

        $html = $view->render('//layouts/main.php', ['content' => 'Hello World°']);

        self::assertStringNotContainsString(
            '3698-PS-22071989',
            $html,
            'Failed asserting that the logged-in username is not rendered after logout.',
        );
        self::assertStringContainsString(
            'Guest',
            $html,
            'Failed asserting that guest state is rendered after logout.',
        );
    }
}
