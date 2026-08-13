<?php

namespace app\models;

use app\models\base\Session as BaseSession;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "session".
 * @property User $user
 */
class Session extends BaseSession {
    public function getUser(): ActiveQuery {
        // Table user itu beda database, jadi kita harus menambahkan prefix database di sini.
        return $this->hasOne(User::class, [
            'id' => 'user_id'
        ])->from(['user' => env('DB_NAME') . '.' . User::tableName()]);
    }
}
