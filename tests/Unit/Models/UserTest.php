<?php

declare(strict_types=1);

namespace app\tests\Unit\Models;

use app\models\User;

final class UserTest extends \Codeception\Test\Unit {

    private ?string $username = null;

    public function _fixtures(): array {
        return [
            'user' => \app\tests\Unit\fixtures\UserFixture::class,
        ];
    }

    protected function _before(): void {
        $this->username = env('TESTING_USERNAME');
    }

    public function testFindUserById() {
        /** @var User $user */
        $user = User::findIdentity(423);

        verify($user)->notEmpty();
        verify($user->username)->equals($this->username);
        verify(User::findIdentity(100))->empty();
    }

    public function testFindUserByAccessToken() {
        /** @var User $user */
        $user = User::findIdentityByAccessToken(env('TESTING_USER_AUTH_KEY'));

        verify($user)->notEmpty();
        verify($user->username)->equals($this->username);
        verify(User::findIdentityByAccessToken('non-existing'))->empty();
    }

    public function testFindUserByUsername() {
        /** @var User $user */
        $user = User::findByUsername($this->username);;

        verify($user)->notEmpty();
        verify(User::findByUsername('not-admin'))->empty();
    }

    /**
     * @depends testFindUserByUsername
     */
    public function testValidateUser() {
        /** @var User $user */
        $user = User::findByUsername($this->username);

        verify($user->validateAuthKey(env('TESTING_USER_AUTH_KEY')))->notEmpty();
        verify($user->validateAuthKey('test102key'))->empty();
    }
}
