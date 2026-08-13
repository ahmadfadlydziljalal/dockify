<?php


$default = [
    'class'    => \yii\db\Connection::class,
    'dsn'      => env('DB_DSN'),
    'username' => env('DB_USER'),
    'password' => env('DB_PASSWORD'),
    'charset'  => 'utf8',
];


return YII_ENV_DEV ? $default : \yii\helpers\ArrayHelper::merge($default, [
    // Schema cache options (for production environment)
    'enableSchemaCache'   => true,
    'schemaCacheDuration' => 60,
    'schemaCache'         => 'cache',
]);
