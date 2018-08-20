<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\City;
use common\models\Timezone;
use yii\helpers\Url;

$this->registerJsFile('https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js', ['position' => \yii\web\View::POS_HEAD]);
Yii::$app->view->registerJsFile('chosen_v1.4.2/chosen.jquery.js', ['position' => \yii\web\View::POS_HEAD]);

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>


    <div class="row">
        <div class="col-lg-6">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'sname') ?>
                <?= $form->field($model, 'email') ?>
                <?= $form->field($model, 'idCity')->dropDownList(City::cityList()) ?>
                <?//= $form->field($model, 'dateBorn') ?>
                <?= $form->field($model, 'sex')->dropDownList(['Мужской', 'Женский'], ['prompt' => 'Выберите пол']) ?>
                <?//= $form->field($model, 'aboutme')->textarea() ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <?= $form->field($model, 'tz_val')->dropDownList(Timezone::timezoneList()) ?>

                <?= $form->field($model, 'big_image_src')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'miniature_src')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'bigminiature')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'idVk')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'idFacebook')->hiddenInput()->label(false) ?>
                Регистрируясь, вы соглашаетесь с <a href="<?=Url::to(['site/confid']) ?>" target="_blank">с политикой конфидициальности</a>
                <div class="form-group">
                    <?= Html::submitButton('Регистрация', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<script>
    $('#signupform-idcity').chosen({no_results_text:'Нет результатов поиска'});

    console.log($("#signupform-tz_val"));
</script>
