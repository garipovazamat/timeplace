<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\City;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model common\models\Headers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="headers-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'id_city')->dropDownList(City::cityList())->label('Город') ?>

    <?= $form->field($picture, 'imageFile')->widget(FileInput::className()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
