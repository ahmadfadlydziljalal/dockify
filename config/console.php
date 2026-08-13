<?php

use app\components\SihrdAuthClient;
use yii\authclient\Collection;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id'                  => 'basic-console',
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => [
        'log',
        'queue',
    ],
    'controllerNamespace' => 'app\commands',
    'aliases'             => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'container'           => [
        'singletons' => [
            SihrdAuthClient::class => static function () {
                /** @var Collection $collection */
                $collection = Yii::$app->get('authClientCollection');
                return $collection->getClient('sihrd');
            },
        ],
    ],
    'components'          => [
        'authManager'          => [
            'class'        => 'yii\rbac\DbManager',
            'defaultRoles' => ['user-default']
        ],
        'authClientCollection' => [
            'class'   => 'yii\authclient\Collection',
            'clients' => [
                'sihrd' => [
                    'class'        => '\app\components\SihrdAuthClient',
                    'clientId'     => env('HRD_CLIENT_ID'),         # Masukkan CLIENT_ID disini
                    'clientSecret' => env('HRD_CLIENT_SECRET'),     # Masukkan CLIENT_SECRET disini
                    'authUrl'      => env('HRD_AUTH_URL'),
                    'tokenUrl'     => env('HRD_TOKEN_URL'),
                    'apiBaseUrl'   => env('HRD_API_BASE_URL'),
                    'apiUserInfo'  => env('HRD_API_USER_INFO'),
                    'viewOptions'  => [
                        'icon' => 'https://cdn-icons-png.flaticon.com/512/2376/2376399.png'
                    ]
                ],
            ],
        ],
        'cache'                => [
            'class' => 'yii\caching\DbCache',
            'db'    => 'supportDb'
        ],
        'log'                  => require __DIR__ . '/log.php',
        'db'                   => $db,
        'supportDb'            => require __DIR__ . '/support_db.php',
        'queue'                => require __DIR__ . '/queue.php',
    ],
    'params'              => $params,

    'controllerMap' => [
        'fixture'         => [ // Fixture generation command line.
            'class'       => 'yii\faker\FixtureController',
            'namespace'   => 'app\tests\Unit\fixtures',
            'interactive' => false
        ],
        'migrate'         => [
            'class'               => 'yii\console\controllers\MigrateController',
            'migrationPath'       => [
                '@app/migrations',                        // default migration project
                '@mdm/admin/migrations',
                '@mdm/autonumber/migrations',
                '@vendor/pheme/yii2-settings/migrations',
                '@yii/rbac/migrations',
            ],
            'migrationNamespaces' => [

            ],
            'db'                  => 'db',
            'interactive'         => false, // optional: supaya tidak muncul notifikasi interactive
        ],
        'migrate-support' => [
            'class'               => 'yii\console\controllers\MigrateController',
            'migrationPath'       => [
                '@yii/log/migrations',       // log core dari Yii2
                '@yii/caching/migrations',   // caching core dari Yii2
                '@yii/web/migrations',   // web core dari Yii2
            ],
            'migrationNamespaces' => [
                'yii\queue\db\migrations'
            ],
            'db'                  => 'supportDb',
            'interactive'         => false,
        ],
    ],

];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => \yii\gii\Module::class,
    ];
    // configuration adjustments for 'dev' environment
    // requires version `2.1.21` of yii2-debug module
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => \yii\debug\Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
