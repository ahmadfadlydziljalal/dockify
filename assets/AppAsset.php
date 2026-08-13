<?php

/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

declare(strict_types=1);

namespace app\assets;

use yii\bootstrap5\BootstrapAsset;
use yii\bootstrap5\BootstrapIconAsset;
use yii\bootstrap5\BootstrapPluginAsset;
use yii\web\AssetBundle;
use yii\web\View;
use yii\web\YiiAsset;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@themes/dist';
    public $baseUrl = '@web';
    public $css = [
        'css/site.min.css',
    ];
    public $js = [
        'js/main.min.js',
    ];

    public $jsOptions = [
        'position' => View::POS_HEAD,
    ];
    public $depends = [
        YiiAsset::class,
        BootstrapIconAsset::class
    ];
}
