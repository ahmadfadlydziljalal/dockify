<?php

$default = [
    'ttr'      => 5 * 600, // Max time for job execution
    'attempts' => 5, // Max number of attempts
];

return array_merge($default, [
    //'class' => yii\queue\file\Queue::class, // untuk file, tetap gunakan php yii queue/listen
    //'as log' => yii\queue\LogBehavior::class, // log queue

    // For database queue, production environment
    'class'        => yii\queue\db\Queue::class,
    'as log'       => yii\queue\LogBehavior::class,
    'db'           => 'supportDb', // DB connection component or its config
    'tableName'    => '{{%queue}}', // Table name
    'channel'      => 'default', // Queue channel key
    'mutex'        => yii\mutex\MysqlMutex::class, // Mutex used to sync queries
    // Important: > 0, so the listener waits for lock instead of failing immediately
    'mutexTimeout' => 5,

]);


