<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Headers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="headers-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать шапку', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'label' => 'Город',
                'value' => function($data) {return $data->city->name;}
            ],
            [
                'format' => 'html',
                'label' => 'ссылка на картинку',
                'value' => function($data) {return Html::a('ссылка', $data->picture->getImagePath());},
            ],

            [
                'label' => 'Действия',
                'format' => 'html',
                'value' => function($data) {
                    return $data->getDeleteButton();
                }
            ]
        ]
            //['class' => 'yii\grid\ActionColumn'],
    ]); ?>

</div>
