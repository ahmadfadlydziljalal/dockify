<?php

namespace app\models\active_queries;

use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the ActiveQuery class for [[\app\models\Session]].
 *
 * @see \app\models\Session
 */
class UserQuery extends \yii\db\ActiveQuery {


    public function map($from = 'id', $to = 'username', $sortBy = 'username'): array {
        return ArrayHelper::map(parent::orderBy($sortBy)->all(), $from, $to);
    }

    /**
     * Find users by name for Select2 widget.
     * For super-admin, search all users
     * @param string $q
     * @return UserQuery
     */
    public function byName(string $q): UserQuery {
        return $this->select([
            'id'   => 'user.id',
            'text' => new Expression("CONCAT(user.nama_panggilan, ' (', user.nama_karyawan, ')')"),
        ])->where(new Expression("( user.nama_panggilan LIKE :q OR user.nama_karyawan LIKE :q )", [':q' => "%$q%"]))
            ->limit(20);
    }


}
