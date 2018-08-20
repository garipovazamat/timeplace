<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use yii\grid\DataColumn;
use \yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\models\City;
use common\models\Country;
use common\models\Event;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchEvent */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Мероприятия';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать мероприятие', ['create'], ['class' => 'btn btn-success']) ?>
        <?php
        $form = ActiveForm::begin([
            'method' => 'get',
            'options' =>[
                'id' => 'select-city',
                'class' => 'head-form',
            ]
        ]);
        ?>
        <?=Html::dropDownList('country', null, Country::countryList(), ['class' => '',
            'onchange'=>'
                sendGet("'.Url::to(['site/selectcity']).'&id_country="+$(this).val(), function(data){
                    $("select#city_choose").html(data);
                    $("select#city_choose").trigger("chosen:updated");
                });
            '
        ]);?>
        <?= $form->field($searchModel, 'city_id')
            ->dropDownList(City::cityList(), ['class' => 'choose_city', 'id' => 'city_choose']) ?>

        <?=Html::submitButton('Отфильтровать')?>
        <?php ActiveForm::end();?>
    </p>
    <?php Pjax::begin()?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            //'user_id',
            [
                'class' => DataColumn::className(),
                'contentOptions' => ['width' => '90px', 'align' => 'center'],
                'format' => 'raw',
                'label' => '',
                'value' => function($data) {
                    $picture = $data->getOnePicture();
                    if($picture)
                        return Html::img($picture->getBigMiniaturePath());
                    else
                        return '';
                }
            ],
            [
                'class' => DataColumn::className(),
                'label' => 'краткое описание события',
                'format' => 'html',
                'value' => function($data){return $data->getShortDesc();}
            ],
            // 'datetime',
            // 'place',
            'city.name:ntext',
            [
                'options' => [
                    'width' => '200px',
                    ],
                'class' => DataColumn::className(),
                'label' => 'модерация',
                'format' => 'html',
                'value' => function($data){
                    if($data->moderated == Event::NO_MODERATED){
                        return '<a href="'.Url::to(["event/moderate", "id" => $data->id, "ismoderated" => 1]).'"><span class="glyphicon glyphicon-thumbs-up btn-sm", style="color: green; font-size: 40px"></span></a>'.
                        '<a href="'.Url::to(["event/moderate", "id" => $data->id, "ismoderated" => 0]).'"><span class="glyphicon glyphicon-thumbs-down btn-sm", style="color: red; font-size: 40px"></span></a>';
                    }
                    else if($data->moderated == Event::MODERATED_YES)
                        return '<a href="'.Url::to(["event/moderateevent", "id" => $data->id]).'"><span class="glyphicon glyphicon-thumbs-up", style="color: green; font-size: 40px"></span></a>';
                    else if($data->moderated == Event::MODERATED_NO)
                        return '<a href="'.Url::to(["event/moderateevent", "id" => $data->id]).'"><span class="glyphicon glyphicon-thumbs-down", style="color: red; font-size: 40px"></span></a>';

                }
            ],
            //'moderated',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end()?>
</div>
