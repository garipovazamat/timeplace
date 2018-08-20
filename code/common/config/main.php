<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'ru-RU',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'image' => [
            'class' => 'ostashevdv\image\ImageManager',
            'cachePath' => '@frontend/web/assets/thumbs/'
        ],
    ],
];
