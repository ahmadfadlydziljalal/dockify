<?php

use app\components\SihrdAuthClient;
use app\models\User;
use yii\authclient\Collection;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/test_db.php';

/**
 * Application configuration shared by all test types
 */
return [
    'id'         => 'basic-tests',
    'basePath'   => dirname(__DIR__),
    'bootstrap'  => [
        \app\tests\Support\MailerBootstrap::class,
    ],
    'aliases'    => [
        '@bower'  => '@vendor/bower-asset',
        '@npm'    => '@vendor/npm-asset',
        '@themes' => '@app/themes',
    ],
    'language'   => 'en-US',
    'components' => [
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
        'db'                   => $db,
        'supportDb'            => require __DIR__ . '/support_db.php',
        'session'              => require __DIR__ . '/session.php',
        'mailer'               => [
            'class'            => \yii\symfonymailer\Mailer::class,
            'messageClass'     => \yii\symfonymailer\Message::class,
            'useFileTransport' => true,
            'viewPath'         => '@app/mail',
        ],
        // 'mailer'               => MailerInterface::class,
        'assetManager'         => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'cache'                => [
            'class' => 'yii\caching\DbCache',
            'db'    => 'supportDb'
        ],
        'user'                 => [
            'identityClass'   => User::class,
            'enableSession'   => true,
            'enableAutoLogin' => false,
        ],
        'request'              => [
            'cookieValidationKey'  => 'test',
            'enableCsrfValidation' => false,
            // but if you absolutely need it set cookie domain to localhost
            /*
            'csrfCookie' => [
                'domain' => 'localhost',
            ],
            */
        ],
        'urlManager'           => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => [
                /** Remove only for controller `site/action` to `/action` */
                '/about'   => '/site/about',
                '/contact' => '/site/contact',
                '/login'   => '/site/login',
                '/logout'  => '/site/logout',
            ],
        ],
    ],
    'container'  => [
        'singletons' => [
            /*MailerInterface::class => [
                'class'            => Mailer::class,
                // send all mails to a file by default.
                'useFileTransport' => true,
                'viewPath'         => '@app/mail',
            ],*/
            SihrdAuthClient::class => static function () {
                /** @var Collection $collection */
                $collection = Yii::$app->get('authClientCollection');
                return $collection->getClient('sihrd');
            },
        ],
    ],
    'params'     => $params,
    'name'       => 'Dockify',
];
