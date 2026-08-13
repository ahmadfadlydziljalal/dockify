<?php

namespace app\models;

use app\models\active_queries\UserQuery;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user".
 * @property  int $id
 * @property array $data
 */
class User extends \mdm\admin\models\User {

    public function rules(): array {
        return ArrayHelper::merge(parent::rules(), [
            [
                ['data',], 'safe'
            ],

            ['username', 'unique', 'targetClass' => User::class, 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],

        ]);
    }

    /**
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        return User::find()->where(['auth_key' => $token])->one();
    }

    public function getPhoto() {
        return ArrayHelper::getValue($this->data, 'karyawan.photo', null);
    }

    public function getJenisKelamin() {
        return ArrayHelper::getValue($this->data, 'karyawan.jenis_kelamin', null);
    }

    public function getAltPhoto() {
        if ($this->getJenisKelamin() === 'Laki - Laki') {
            return '<i class="bi bi-gender-male"></i>';
        }
        return '<i class="bi bi-gender-female"></i>';
    }

    public function getNickname() {
        return ArrayHelper::getValue($this->data, 'karyawan.nama_panggilan', null);
    }


    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find(): UserQuery {
        return new UserQuery(get_called_class());
    }

    public function getAuths(): ActiveQuery {
        return $this->hasMany(Auth::class, ['user_id' => 'id']);
    }


}
