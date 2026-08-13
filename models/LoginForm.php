<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\authclient\OAuth2;
use yii\base\Model;
use yii\base\Security;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 */
class LoginForm extends Model {
    public string $username = '';
    public string $password = '';
    public bool $rememberMe = true;
    private User|null $_user = null;
    private bool $_userLoaded = false;

    public function __construct(private readonly Security $security, $config = []) {
        parent::__construct($config);
    }

    /**
     * @return array the validation rules.
     */
    public function rules(): array {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array|null $params the additional name-value pairs given in the rule
     */
    public function validatePassword(string $attribute, array|null $params): void {
        if (!$this->hasErrors()) {

            // in production or development, we don't validate the password here,
            // because the password is validated by OAuth2 Resource Owner Password Grant

            if (YII_ENV_TEST) {
                $user = $this->getUser();
                if (!$user || !$this->security->validatePassword($this->password, $user->password_hash)) {
                    $this->addError($attribute, 'Incorrect username or password.');
                }
            }
        }

    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login(): bool {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser(): User|null {
        if (!$this->_userLoaded) {
            $this->_user = User::findByUsername($this->username);
            $this->_userLoaded = true;
        }

        return $this->_user;
    }

    /**
     * Peraturan, hanya user yang bertipe khusus, yaitu
     *  - super-admin yang boleh login
     * @throws Exception
     */
    public function loginByOauth2ResourceOwnerPassword(OAuth2 $client): bool|User {

        # Set Session
        Yii::$app->session->set('jwt', $client->getAccessToken());

        # Get attribute dari OAuth2
        $attributes = $client->getUserAttributes();
        $auth = Auth::find()->where([
            'source'    => $client->getId(),
            'source_id' => ArrayHelper::getValue($attributes, 'user.id'),
        ])->one();

        # Cek kalau user sudah terdaftar dari SIHRD atau another OAuth2 ?
        if ($auth) {
            # Update data terakhir user tersebut By User info
            $this->updateUser($auth->user, $attributes, $client);
            return $auth->user;
        } else {
            # User belum terdaftar ...
            return $this->createUser($attributes, $client);
        }
    }

    protected function createUser($attributes, $client): User {
        $user = new User([
            'username' => ArrayHelper::getValue($attributes, 'user.username'),
            'email'    => ArrayHelper::getValue($attributes, 'user.email'),
            'password' => $this->password,
            'data'     => $attributes,
        ]);
        $user->setAttribute(
            'id', ArrayHelper::getValue($attributes, 'user.id'),
        );
        $user->generateAuthKey();
        $user->generatePasswordResetToken();

        if ($user->save()) {
            # Save sebagai AuthUser baru
            $auth = new Auth([
                'user_id'   => $user->id,
                'source'    => $client->getId(),
                'source_id' => (string) ArrayHelper::getValue($attributes, 'user.id'),
            ]);
            $auth->save();

            # Cek kalau user account tersebut di SIHRD adalah super-admin ?
            $authManager = Yii::$app->authManager;
            if (ArrayHelper::getValue($attributes, 'roles.super-admin')) {
                $sa = $authManager->getRole('super-admin');
                $authManager->assign($sa, $user->id);
            }

            Yii::$app->session->setFlash('success', 'Anda sudah bergabung via ' . ucfirst($client->getId()) . ', dan Selamat Datang...! ');
            Yii::$app->user->login($user, 86400);
        }

        return $user;
    }

    /**
     * @param User $user
     * @param $attributes
     * @param $client
     * @return void
     * @throws Exception
     */
    protected function updateUser(User $user, $attributes, $client): void {

        $user->email = ArrayHelper::getValue($attributes, 'user.email');
        $user->username = ArrayHelper::getValue($attributes, 'user.username');
        $user->data = $attributes;

        # Update user di database
        $user->save(false);

        # Set flash ke UI untuk informasi ke user
        Yii::$app->session->setFlash('success', 'Login by ' . strtoupper($client->getId()) . ' berhasil dan Selamat Datang...! ');

        # Assign user berhasil login
        Yii::$app->user->login($user, 84000);
    }
}
