<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class OldAppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/mystyle.css',
        'chosen_v1.4.2/chosen.css',
        //'css/fonts.css',
        //'css/main.css',
        //'css/media.css',
    ];
    public $js = [
        //'https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js',
        'chosen_v1.4.2/chosen.jquery.js',
        'js/myjs.js',
        //'js/common.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}