<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/site.css',
        //'css/mystyle.css',
        'chosen_v1.4.2/chosen.css',
        'css/fonts.css',
        'css/main.css',
        'css/media.css',
        'css/modif.css'
    ];
    public $cssOptions = [
        'position' => \yii\web\View::POS_END
    ];
    public $js = [
        //'https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js',
        'chosen_v1.4.2/chosen.jquery.js',
        'js/myjs.js',
        'js/common.js',
        'libs/Magnific-Popup/jquery.magnific-popup.min.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}
