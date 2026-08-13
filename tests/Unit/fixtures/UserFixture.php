<?php

namespace app\tests\Unit\fixtures;

use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = 'app\models\User'; // Model terkait tabel user
    public $dataFile = __DIR__ . '/data/user.php';

}
