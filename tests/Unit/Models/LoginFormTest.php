<?php

declare(strict_types=1);

namespace app\tests\Unit\Models;

use app\models\LoginForm;
use Yii;
use yii\base\Security;

final class LoginFormTest extends \Codeception\Test\Unit
{
    private ?string $username = null;
    private ?string $password = null;

    private ?LoginForm $_model = null;

    public function _fixtures(): array
    {
        return [
            'user' => \app\tests\Unit\fixtures\UserFixture::class,
        ];
    }

    protected function _before(): void {
        $this->username = env('TESTING_USERNAME');
        $this->password = env('TESTING_PASSWORD');
    }

    protected function _after(): void

    {
        Yii::$app->user->logout();
    }

    public function testLoginNoUser()
    {
        $this->_model = new LoginForm(
            new Security(),
            [
                'username' => 'not_existing_username',
                'password' => 'not_existing_password',
            ],
        );

        verify($this->_model->login())->false();
        verify(Yii::$app->user->isGuest)->true();
    }

    public function testLoginWrongPassword()
    {
        $this->_model = new LoginForm(
            new Security(),
            [
                'username' => $this->username,
                'password' => 'wrong_password',
            ],
        );

        verify($this->_model->login())->false();
        verify(Yii::$app->user->isGuest)->true();
        verify($this->_model->errors)->arrayHasKey('password');
    }

    public function testLoginCorrect()
    {
        $this->_model = new LoginForm(
            new Security(),
            [
                'username' => $this->username,
                'password' => $this->password,
            ],
        );

        verify($this->_model->login())->true();
        verify(Yii::$app->user->isGuest)->false();
        verify($this->_model->errors)->arrayHasNotKey('password');
    }
}
