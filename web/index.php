<?php

declare(strict_types=1);



// comment out the following two lines when a flexible environment is needed
//defined('YII_DEBUG') or define('YII_DEBUG', true);
//defined('YII_ENV') or define('YII_ENV', 'dev');

// Load environment variables from the.env file if it exists
// is you use the two lines above ?, you can comment them out the following lines
// ------------------------
$yiiDebug = getenv('YII_DEBUG');
$yiiEnv = getenv('YII_ENV') ?: 'dev';

defined('YII_DEBUG') or define(
    'YII_DEBUG',
    $yiiDebug === false ? true: filter_var($yiiDebug, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? true,
);
defined('YII_ENV') or define('YII_ENV', $yiiEnv);
// ------------------------

const DOTENV_FILE = '.env';
const DOTENV_OVERLOAD = false;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
