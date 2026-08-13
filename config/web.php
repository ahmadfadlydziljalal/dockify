<?php

use app\components\SihrdAuthClient;
use app\models\User;
use yii\authclient\Collection;
use yii\gii\Module;
use yii\mail\MailerInterface;
use yii\symfonymailer\Mailer;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'aliases'    => [
        '@bower'  => '@vendor/bower-asset',
        '@npm'    => '@vendor/npm-asset',
        '@themes' => '@app/themes',
    ],
    'as access'  => [
        'class'        => 'mdm\admin\components\AccessControl',
        'allowActions' => [
//            'site/*',
//            'admin/*',
//            'debug/*',
//            'gii/*',
        ]
    ],
    'id'         => 'basic',
    'basePath'   => dirname(__DIR__),
    'bootstrap'  => ['log'],
    'components' => [
        'assetManager'         => [
            'bundles'         => [
                yii\bootstrap5\BootstrapAsset::class       => false, // CSS sudah digantikan site.min.css
                yii\bootstrap5\BootstrapPluginAsset::class => false, // JS sudah digantikan main.min.js
                yii\bootstrap5\BootstrapIconAsset::class   => [
                    'sourcePath' => '@vendor/twbs/bootstrap-icons/font',
                    'css'        => [
                        'bootstrap-icons.css'
                    ],
                ],
            ],
            'appendTimestamp' => false,
            'dirMode'         => 0755,
            'forceCopy'       => YII_ENV_DEV,
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
        'authManager'          => [
            'class'        => 'yii\rbac\DbManager',
            'defaultRoles' => ['user-default']
        ],
        'formatter'            => [
            'defaultTimeZone'        => 'Asia/Jakarta',
            'dateFormat'             => 'php:d-m-Y',
            'datetimeFormat'         => 'php:d-m-Y H:i',
            'timeFormat'             => 'php:H:i',
            'thousandSeparator'      => ",",
            'decimalSeparator'       => '.',
            'currencyCode'           => "Rp.",
            'numberFormatterOptions' => [
                NumberFormatter::MIN_FRACTION_DIGITS => 0,
                NumberFormatter::MAX_FRACTION_DIGITS => 2,
            ],
            'nullDisplay'            => '',
            'locale'                 => 'id-ID', //your language locale
        ],
        'request'              => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'T1aAvi-qy0yEVkF4_v8E1E_9RA2iRaoQ',
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
        'errorHandler'         => [
            'errorAction' => 'site/error',
        ],
        'mailer'               => MailerInterface::class,
        'log'                  => require __DIR__ . '/log.php',
        'db'                   => $db,
        'supportDb'            => require __DIR__ . '/support_db.php',
        'settings'             => [
            'class'          => 'pheme\settings\Module',
            'sourceLanguage' => 'en',
        ],
        'session'              => require __DIR__ . '/session.php',
        'pdf'                  => [
            'class'        => kartik\mpdf\Pdf::class,
            'format'       => kartik\mpdf\Pdf::FORMAT_A4,
            'orientation'  => kartik\mpdf\Pdf::ORIENT_PORTRAIT,
            'destination'  => kartik\mpdf\Pdf::DEST_BROWSER,
            'cssFile'      => '@app/themes/v2/dist/css/pdf.min.css',
            'methods'      => [],
            'marginTop'    => '5',
            'marginHeader' => '5',
            'marginRight'  => '5',
            'marginLeft'   => '5',
            'marginBottom' => '5',
            'options'      => [
                'showWatermarkText' => true,
                'useSubstitutions'  => false,
                'simpleTables'      => false,
            ],
        ],
        'i18n'                 => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    /*'basePath' => '@app/messages', // if advanced application, set @frontend/messages
                    'fileMap'  => [
                        'app'       => 'app.php',
                        'rbac'      => 'rbac.php',
                        'dashboard' => 'dashboard.php',
                    ],*/
                ],
            ],
        ],
        's3Client'             => [
            'class'       => 'app\components\s3\S3ClientComponent',
            'region'      => env('SPACES_DO_REGION'),
            'endpoint'    => env('SPACES_DO_ENDPOINT'),
            'credentials' => [
                'key'    => env('SPACES_DO_KEY'),
                'secret' => env('SPACES_DO_SECRET'),
            ],
            'bucket'      => env('SPACES_DO_BUCKET'),
            'baseUrl'     => env('SPACES_DO_URL'),
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
        'queue'                => require __DIR__ . '/queue.php',
    ],
    'container'  => [
        'singletons'  => [
            MailerInterface::class => [
                'class'            => Mailer::class,
                // send all mails to a file by default.
                'useFileTransport' => true,
                'viewPath'         => '@app/mail',
            ],
            SihrdAuthClient::class => static function () {
                /** @var Collection $collection */
                $collection = Yii::$app->get('authClientCollection');
                return $collection->getClient('sihrd');
            },
        ],
        'definitions' => [
            yii\widgets\LinkPager::class          => [
                'class'          => yii\bootstrap5\LinkPager::class,
                'maxButtonCount' => 3
            ],
            yii\grid\GridView::class              => [
                'layout' => "
                    <div class='d-flex flex-column gap-2'>
                        <div class='table-responsive'>
                            {items}
                        </div>
                        <div class='d-flex flex-row flex-wrap align-items-baseline'>
                             {summary}
                            <div class='ms-auto' >
                                {pager}
                            </div>
                        </div>
                    </div>
                ",
            ],
            yii\data\Pagination::class            => ['pageSize' => 10],
            kartik\grid\GridView::class           => [
                'layout'         => "
                    <div class='d-flex flex-column gap-2'>
                        {items}
                        <div class='d-flex flex-row flex-wrap align-items-baseline justify-content-center justify-content-md-start text-center text-md-start w-100'>
                            <div class='me-md-auto'>
                                {summary}
                            </div>
                            <div class='mt-2 mt-md-0'>
                                {pager}
                            </div>
                        </div>
                    </div>
                ", // {export}, saya butuh ini masih
                'responsiveWrap' => false,
            ],
            kartik\date\DatePicker::class         => [
                'type'          => kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
                'pickerIcon'    => '<i class="bi bi-calendar"></i>',
                'removeIcon'    => '<i class="bi bi-x-lg"></i>',
                'pluginOptions' => [
                    'todayHighlight' => true,
                    'todayBtn'       => true,
                    'autoclose'      => true,
                    'format'         => 'dd-mm-yyyy'
                ]
            ],
            kartik\datetime\DateTimePicker::class => [
                'type'          => kartik\datetime\DateTimePicker::TYPE_INPUT,
                'options'       => [
                    'class' => 'date-time-picker'
                ],
                'pluginOptions' => [
                    'autoclose'      => true,
                    'minuteStep'     => 1,
                    'position'       => 'top',
                    'todayHighlight' => true,
                    'format'         => 'dd-mm-yyyy hh:ii',
                ]
            ],
        ]
    ],
    'modules'    => [
        'admin'       => [
            'class'    => 'mdm\admin\Module',
            'viewPath' => '@app/views/mdm',
        ],
        'datecontrol' => [
            'class'              => 'kartik\datecontrol\Module',
            'ajaxConversion'     => true,
            'displaySettings'    => [
                kartik\datecontrol\Module::FORMAT_DATE     => 'php:d-m-Y',
                kartik\datecontrol\Module::FORMAT_TIME     => 'php:H:i:s',
                kartik\datecontrol\Module::FORMAT_DATETIME => 'php:d-m-Y H:i',
            ],
            'saveSettings'       => [
                kartik\datecontrol\Module::FORMAT_DATE     => 'php:Y-m-d',
                kartik\datecontrol\Module::FORMAT_TIME     => 'php:H:i:s',
                kartik\datecontrol\Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
            ],
            'autoWidget'         => true,
            'autoWidgetSettings' => [
                /** @see DatePicker */
                /** @see https://bootstrap-datepicker.readthedocs.io/en/latest/index.html */
                kartik\datecontrol\Module::FORMAT_DATE     => [
                    'type'          => kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
                    'pickerIcon'    => '<i class="bi bi-calendar"></i>',
                    'removeButton'  => false,
                    'pluginOptions' => [
                        'autoclose'      => true,
                        'todayHighlight' => true,
                        'orientation'    => 'bottom-left',
                        //'zIndexOffset' => 10000,
                    ],
                ],
                /* @see https://github.com/sabinus52/bootstrap-datetimepicker */
                kartik\datecontrol\Module::FORMAT_DATETIME => [
                    'type'          => kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
                    'pickerIcon'    => '<i class="bi bi-calendar"></i>',
                    'removeButton'  => false,
                    'pluginOptions' => [
                        'minuteStep'     => 1,
                        'autoclose'      => true,
                        'todayHighlight' => true,
                        'pickerPosition' => 'bottom-left',
                        'zIndexOffset'   => 10000,
                        'icons'          => [
                            'leftArrow'  => 'bi-arrow-left',
                            'rightArrow' => 'bi-arrow-right',
                        ],
                    ]
                ],
                /** @see https://github.com/jdewit/bootstrap-timepicker */
                kartik\datecontrol\Module::FORMAT_TIME     => [
                    'addon'         => '<i class="bi bi-clock"></i>',
                    'value'         => '',
                    'pluginOptions' => [
                        'showSeconds'  => true,       // tampilkan detik
                        'showMeridian' => false,     // format 24 jam
                        'defaultTime'  => false,      // biar gak automatically isi waktu saat ini
                        'minuteStep'   => 1,
                        'secondStep'   => 1,
                    ],
                    'options'       => [
                        'placeholder' => '00:00:00',
                    ],
                ],
            ],
            'widgetSettings'     => [],
        ],
        'settings'    => [
            'class'          => 'pheme\settings\Module',
            'sourceLanguage' => 'en',
            'viewPath'       => '@app/views/settings',
        ],
        'gridview'    => [
            'class' => 'kartik\grid\Module',
        ],
    ],
    'name'       => 'Dockify',
    'params'     => $params,
    'timeZone'   => 'Asia/Jakarta',
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class'      => \yii\debug\Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', "*"],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class'      => Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', "*"],
    ];
}

return $config;
