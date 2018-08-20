<?php
use yii\bootstrap\Nav;
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">
<?
    echo Nav::widget([
        'items' => [
            ['label' => 'Пользователи', 'url' => Url::to(['user/index'])],
            ['label' => 'Мероприятия', 'url' => Url::to(['event/index'])],
            ['label' => 'Категории', 'url' => Url::to(['category/index'])]
        ],
        'options' => [
            'class' =>'nav-pills nav-stacked',
            'style' => 'text-align: center'
        ], // set this to nav-tab to get tab-styled navigation
    ]);
?>
</div>
