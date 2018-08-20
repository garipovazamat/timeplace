<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\City;
use kartik\datetime\DateTimePicker;
use kartik\file\FileInput;
use common\models\Category;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;
use kolyunya\yii2\widgets\MapInputWidget;
use common\models\Country;

/* @var $this yii\web\View */
/* @var $model common\models\Event */
/* @var $form yii\widgets\ActiveForm */

//$this->registerJsFile('https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js', ['position' => \yii\web\View::POS_HEAD]);
//Yii::$app->view->registerJsFile('chosen_v1.4.2/chosen.jquery.js', ['position' => \yii\web\View::POS_HEAD]);

for($i=0; $i<count($pictures); $i++){
    if(!$pictures[$i]->isNewRecord){
        $pluginOpt[$i] = ['initialPreview'=>[
            Html::img($pictures[$i]->getImagePath(), ['class'=>'file-preview-image', 'alt'=>'Фото события', 'title'=>'Фото события']),
        ],
            'initialCaption' => "Картинка для события",
            'showUpload' => false,
            'browseLabel' => '',
            'removeLabel' => '',
            'mainClass' => 'input-group-lg'
        ];
    }
    else $pluginOpt[$i] = [
        'showUpload' => false,
        'browseLabel' => '',
        'removeLabel' => '',
        'mainClass' => 'input-group-lg'
    ];
}

$model->user_id = Yii::$app->user->id;
$model->opened = 1;
?>
<style>
    .controls {
        margin-top: 10px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }
    #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 300px;
    }
    #pac-input:focus {
        border-color: #4d90fe;
    }
    .pac-container {
        font-family: Roboto;
    }
    #type-selector {
        color: #fff;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
    }
    #type-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
    }
</style>

<header class="header_main_body">
    <div class="row">
        <div class="left-head col-sm-6">
            <div class="bread-crumbs">
                <a href="<?=Url::to(['user/index'])?>">Моя страница</a>
            </div>
            <h3><?=($model->isNewRecord) ? 'Создание' : 'Изменение'?> события</h3>
        </div>
        <div class="right-head col-sm-6">
        </div>
    </div>
</header>
<section class="main_section_main_body creat-event">
    <div class="col-md-4 col-md-push-8">
        <?=$this->render('/layouts/adversing')?>
    </div>
    <div class="col-md-8 col-md-pull-4">
        <?php $form = ActiveForm::begin(['options' => [
            'class' => 'edit-uzer search-people-form',
            'enctype' => 'multipart/form-data'
        ]]); ?>
        <?= $form->field($model, 'user_id')->hiddenInput()->label(false);?>

        <?= $form->errorSummary($model) ?>
        <div class="select-wrap">
            <label class="control-label">Название</label>
            <?= Html::activeTextInput($model, 'name'); ?>
        </div>
        <div class="select-wrap clearfix">
            <label for="about" class="control-label">Описание</label>
            <?= Html::activeTextarea($model, 'fulldesc', ['rows' => 10])?>
        </div>
        <div class="select-wrap">
            <label for="price-event" class="control-label">Стоимость</label>
            <?= Html::activeTextInput($model, 'cost'); ?>
        </div>
        <div class="select-wrap">
            <label class="control-label" for="country_search-people">Страна</label>
            <?=Html::dropDownList('country', null, Country::countryList(), ['class' => '',
                'onchange'=>'
                sendGet("'.Url::to(['site/selectcity']).'&id_country="+$(this).val(), function(data){
                    $("select#city_event").html(data);
                    $("select#city_event").trigger("chosen:updated");
                });
            '
            ]);?>
        </div>
        <div class="select-wrap">
            <label class="control-label" for="city_search-people">Город</label>
            <?=Html::activeDropDownList($model, 'city_id', City::cityList(), [
                'class' => 'form-control choose_city',
                'id' => 'city_event',
            ])?>

        </div>
        <div class="select-wrap">
            <label for="place-event" class="control-label">Место (в указанном городе)</label>
            <?= Html::activeTextInput($model, 'place'); ?>
        </div>
        <div class="select-wrap">
            <label for="event-open">Тип события</label>
            <?=Html::activeDropDownList($model, 'opened', ['закрытое', 'открытое'])?>
        </div>
        <div class="select-wrap field-event-coordinates">
                <?= $form->field($model, 'coordinates')->widget(MapInputWidget::className(), [
                    'key' => 'AIzaSyA4t1iQVtgKE7qSaPI6xmtlIvHZzs34_DY',
                    'latitude' => 60,
                    'longitude' => 60,
                    'zoom' => 3,
                    'width' => '100%',
                    'height' => '500px',
                    'mapType' => 'roadmap',
                    'animateMarker' => true,
                    'alignMapCenter' => false,
                    'pattern' => '%longitude%,%latitude%'
                ]) ?>
        </div>
        <?= $form->field($model, 'datetime')->widget(DateTimePicker::classname(), [
            'options' => ['placeholder' => 'Выберите время для события ...'],
            'attribute' => 'datetime',
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd hh:ii'
            ]
        ]); ?>
        <?= $form->field($model, 'datetime_to')->widget(DateTimePicker::classname(), [
            'options' => ['placeholder' => 'Выберите время для события ...'],
            'attribute' => 'datetime_to',
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd hh:ii'
            ]
        ]); ?>
        <div class="select-wrap">
            <label for="category" class="control-label">Категория</label>
            <?= Html::activeDropDownList($event_category[0], '[0]id_category', Category::depCategoryList(1), [
                'id'=>'subcat-id-0',
                'prompt' => 'Выберите категорию',
                'onchange' => '
                    sendGet( "'.Url::to(['/category/getsubcat2']).'&id="+$(this).val(), function(data) {
                    $("#subcat-id-1").html(data);
                    });
                '
            ]);?>
        </div>
        <label for="category" class="control-label">Подкатегория</label>
        <?= Html::activeDropDownList($event_category[1], '[1]id_category', [], [
            'class' => 'form-control',
            'id'=>'subcat-id-1',
            'prompt' => 'подкатегория..'
        ]);?>
        <h5>Загрузите изображения</h5>
        <div class="load-img-wrap row">
            <?php for($i=0; $i<count($pictures); $i++){?>
            <div class="col-md-6 col-sm-6 load-img-item-wrap">
            <?= $form->field($pictures[$i], "[$i]imageFile")->widget(FileInput::classname(), [
                'pluginOptions' => $pluginOpt[$i]
            ]);?>
            </div>
            <?php }?>
        </div>
        <div class="btn-creat-event">
            <button class="btn red_btn"><?=($model->isNewRecord) ? 'Создать' : 'Изменить'?> событие</button>
        </div>
        </form>
    </div>
</section>