<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 12.11.2015
 * Time: 17:51
 * @var $model common\models\User
 * @var $photo common\models\Picture
 * @var $this \yii\web\View
 */

use \yii\widgets\ActiveForm;
use \common\models\City;
use kartik\date\DatePicker;
use yii\helpers\Html;
use kartik\file\FileInput;
use common\models\Timezone;
use common\models\Country;
use yii\helpers\Url;

/*$this->registerJsFile('https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js', ['position' => \yii\web\View::POS_HEAD]);
Yii::$app->view->registerJsFile('chosen_v1.4.2/chosen.jquery.js', ['position' => \yii\web\View::POS_HEAD]);*/

if (isset($model->id_picture)) {
    $pluginOpt = ['initialPreview' => [
        Html::img($photo->getImagePath(), ['class' => 'file-preview-image', 'alt' => 'Фото пользователя', 'title' => 'Фото пользователя']),
    ],
        'initialCaption' => "Фото пользователя",
        'showUpload' => false,
        'browseLabel' => '',
        'removeLabel' => '',
        'mainClass' => 'input-group-lg'
    ];
} else $pluginOpt = [
    'showUpload' => false,
    'browseLabel' => '',
    'removeLabel' => '',
    'mainClass' => 'input-group-lg'
];
?>

<header class="header_main_body">
    <div class="row">
        <div class="left-head col-sm-6">
            <div class="bread-crumbs">
                <a href="<?=Url::to(['user/index'])?>">Моя страница</a>
            </div>
            <h3>Настройки профиля</h3>
        </div>
        <div class="right-head col-sm-6">
        </div>
    </div>
</header>
<section class="main_section_main_body profile-setup">
    <div class="col-md-4 col-md-push-8">
        <?=$this->render('/layouts/adversing')?>
    </div>
    <div class="col-md-8 col-md-pull-4">
        <div class="tabs">
            <ul class="tabs_controls clearfix row">
                <li class="tabs_controls-item active col-md-4">
                    Общие
                </li>
                <!--<li class="tabs_controls-item col-md-4">
                    Приватность
                </li>
                <!--<li class="tabs_controls-item col-md-4">
                    Черный список
                </li>!-->
            </ul>
            <div class="tabs_list">
                <div class="wrap_tabs_content main-profile-setup">
                    <!--Общие!-->
                    <ul class="tabs_content">
                        <li class="tabs_item clearfix">
                            <?php $form = ActiveForm::begin(['options' => [
                                'class' => 'edit-uzer search-people-form',
                                'enctype' => 'multipart/form-data'
                            ]])?>
                            <?= $form->field($model, 'username',[
                                'template' => '<div class="select-wrap">{label}{input}</div>',
                            ])->textInput(['maxlength' => true]) ?>
                            <?= $form->field($model, 'sname', [
                                'template' => '<div class="select-wrap">{label}{input}</div>',
                            ])->textInput(['maxlength' => true]) ?>
                            <?= $form->field($model, 'sex', [
                                'template' => '<div class="select-wrap gender">{label}{input}</div>',
                            ])->dropDownList(['Мужской', 'Женский'], [
                                    'prompt' => 'Выберите пол',
                                    'class' => 'clan_search-people',
                                    'id' => 'clan_search-people',
                                ]) ?>
                            <?= $form->field($model, 'date_born', [
                                'template' => '<div class="select-wrap date_OB">{label}{input}</div>',
                            ])->widget(DatePicker::className(),
                                ['pluginOptions' => [
                                    'format' => 'yyyy-mm-dd',
                                    'todayHighlight' => true
                                ]])?>

                            <div class="form-group">
                                <label class="control-label" for="city">Страна</label>
                                <?= Html::dropDownList('country', null, Country::countryList(), ['id' => 'country',
                                    'class' => 'form-control modif_country',
                                    'onchange'=>'
                                    $.post( "'.Url::to(['site/selectcity']).'&id_country="+$(this).val(), function(data) {
                                    $("select#city").html(data);
                                    $("select#city").trigger("chosen:updated");
                                    });',
                                    'template' => '<div class="select-wrap">{label}{input}</div>',
                                ]); ?>
                            </div>
                            <?= $form->field($model, 'id_city')->
                                                dropDownList(City::cityList(), ['id' => 'city']); ?>

                            <?=$form->field($model, 'id_tz', [
                                'template' => '<div class="select-wrap">{label}{input}</div>',
                            ])->dropDownList(Timezone::timezoneList(), ['id' => 'Timezone', 'class' => '']) ?>

                            <?= $form->field($model, 'aboutme', [
                                'template' => '<div class="select-wrap clearfix">{label}{input}</div>',
                            ])->textarea(['rows' => 5]) ?>

                            <?= $form->field($model, 'pagevk', [
                                'template' => '<div class="select-wrap">{label}{input}</div>',
                            ])->textInput(['maxlength' => true]) ?>

                                <div class="foto_uzer_wrap">
                                    <div class="foto_uzer">
                                        <div class="foto_uzer-img" style='background-image: url("<?=$model->getPhoto()->getImagePath()?>");'>
                                        </div>
                                    </div>
                                    <?=$form->field($photo, 'imageFile')
                                        ->fileInput(['class' => 'input-file'])
                                        ->label(false)?>
                                </div>
                                <div class="btn-uzer-page btn-group">
                                    <?= Html::submitButton('Сохранить изменения', ['class' => 'btn red_btn']) ?>
                                    <?= Html::a('Удалить страницу',
                                        ['user/delete'],
                                        [
                                            'class' => 'del_page btn grey_btn open-popup',
                                            'data' => [
                                                'confirm' => 'Вы действительно хотите удалить мероприятие?',
                                                'method' => 'post',
                                            ]
                                        ]
                                    );?>
                                </div>
                            <?php ActiveForm::end() ?>
                        </li>
                    </ul>
                </div>
                <div class="wrap_tabs_content">
                    <!--Приватность!-->
                    <form class="edit-uzer search-people-form private_form">
                        <div class="select-wrap">
                            <label for="clan_search-people" class="control-label">Кто может просматривать мои события</label>
                            <select name="clan_search-people" id="clan_search-people">
                                <option value="0">Все пользователи</option>
                                <option value="1">Некоторые друзья</option>
                                <option value="2">Только друзья</option>
                                <option value="3">Только я</option>
                            </select>
                        </div>
                    </form>
                </div>
                <!--<div class="wrap_tabs_content">
                    <form class="form-search">
                        <div class="search-word">
                            <input type="text" class="input-search" placeholder="Введите ссылку на страницу пользователя">
                        </div>
                        <div class="search-button">
                            <button class="btn-search">Поиск</button>
                        </div>
                    </form>
                    <h5>В черном списке <span>31</span> человек</h5>
                    <ul class="tabs_content">
                        <li class="tabs_item clearfix">
                            <div class="avatar_tabs_item">
                                <img src="img/avatar.png" alt="">
                            </div>
                            <div class="center_tabs_item">
                                <div class="name-uzer">
                                    <a href="#">Имя Пользователя<span class="off-uzer"></span></a>
                                </div>
                            </div>
                            <div class="right_tabs_item">
                                <a href="#" class="tabs_btn red_btn">Добавить в черный список</a>
                            </div>
                        </li>
                        <li class="tabs_item clearfix">
                            <div class="avatar_tabs_item">
                                <img src="img/avatar.png" alt="">
                            </div>
                            <div class="center_tabs_item">
                                <div class="name-uzer">
                                    <a href="#">Имя Пользователя<span class="off-uzer"></span></a>
                                </div>
                            </div>
                            <div class="right_tabs_item">
                                <a href="#" class="tabs_btn red_btn">Добавить в черный список</a>
                            </div>
                        </li>
                        <li class="tabs_item clearfix">
                            <div class="avatar_tabs_item">
                                <img src="img/avatar.png" alt="">
                            </div>
                            <div class="center_tabs_item">
                                <div class="name-uzer">
                                    <a href="#">Имя Пользователя<span class="off-uzer"></span></a>
                                </div>
                            </div>
                            <div class="right_tabs_item">
                                <a href="#" class="tabs_btn">Убрать из черного списка</a>
                            </div>
                        </li>
                    </ul>
                </div>!-->
            </div>
        </div>
    </div>
</section>

<script>
    showPage(0);
</script>
