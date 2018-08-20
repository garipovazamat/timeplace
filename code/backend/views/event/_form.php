<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;
use common\models\City;
use kartik\datetime\DateTimePicker;
use common\models\Category;

/* @var $this yii\web\View */
/* @var $model common\models\Event */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile('https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js', ['position' => \yii\web\View::POS_HEAD]);
Yii::$app->view->registerJsFile('chosen_v1.4.2/chosen.jquery.js', ['position' => \yii\web\View::POS_HEAD]);
?>

<div class="event-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->dropDownList(User::usersList())?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'fulldesc')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'datetime')->widget(DateTimePicker::classname(), [
        'options' => ['placeholder' => 'Enter event time ...'],
        'pluginOptions' => [
            'autoclose' => true
        ]
    ]); ?>
    <?= $form->field($model, 'city_id')->dropDownList(City::cityList())?>
    <?= $form->field($model, 'place')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'moderated')->checkbox() ?>
    <?= $form->field($event_category, 'id_category')->dropDownList(Category::categoryList())?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    $('#event-city_id').chosen({no_results_text:'Нет результатов поиска'});
    $('#event-user_id').chosen({no_results_text:'Нет результатов поиска'});
    $('#eventcategory-id_category').chosen({no_results_text:'Нет результатов поиска'});
</script>
