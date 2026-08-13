<?php

return [
    'name' => 'session',
    'timeout' => 86400, // 1 Day
    'class' => 'yii\web\DbSession',
    'db' => 'supportDb',
    'cookieParams' => [
        'sameSite' => PHP_VERSION_ID >= 70300 ? yii\web\Cookie::SAME_SITE_LAX : null,
    ],
    'writeCallback' => function ($session) {
        return [
            'user_id' => Yii::$app->user->id,
            'last_write' => time(),
        ];
    }
];
