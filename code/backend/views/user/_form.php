<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\City;
use kartik\file\FileInput;
use \kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
/* @var $user_role app\models\Roles */
/* @var $photo common\models\Picture */

$this->registerJsFile('https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js', ['position' => \yii\web\View::POS_HEAD]);
Yii::$app->view->registerJsFile('chosen_v1.4.2/chosen.jquery.js', ['position' => \yii\web\View::POS_HEAD]);
$model->status = 10;

if (isset($model->id_picture)) {
    $pluginOpt = ['initialPreview' => [
        Html::img($photo->getImagePath(), ['class' => 'file-preview-image', 'alt' => 'Фото пользователя', 'title' => 'Фото пользователя']),
    ],
        'initialCaption' => "Фото пользователя",
    ];
} else $pluginOpt = [];
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <?//= $form->field($model, 'status')->textInput() ?>
    <?= $form->field($model, 'sname')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'aboutme')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'pagevk')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'id_city')->dropDownList(City::cityList()) ?>
    <?= $form->field($model, 'date_born')->widget(DatePicker::className(),
        [
    'pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'todayHighlight' => true
    ]])?>
    <?=$form->field($photo, 'imageFile')->widget(FileInput::classname(), [
        'pluginOptions' => $pluginOpt
    ])?>
    <?//= $form->field($model, 'last_visit')->textInput() ?>
    <?=(Yii::$app->user->can('editrole'))?$form->field($user_role, 'item_name')->dropDownList([
        'admin' => 'Администратор',
        'moderator' => 'Модератор',
        'user' => 'Пользователь'
    ]):'' ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    $('#user-id_city').chosen({no_results_text:'Нет результатов поиска'});
</script>