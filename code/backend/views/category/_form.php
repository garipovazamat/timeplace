<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */
/* @var $picture common\models\Picture; */
/* @var $subcat common\models\Subcategory; */

$this->registerJsFile('https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js', ['position' => \yii\web\View::POS_HEAD]);
Yii::$app->view->registerJsFile('chosen_v1.4.2/chosen.jquery.js', ['position' => \yii\web\View::POS_HEAD]);

if (isset($model->id_picture)){
    $pluginOpt1 = ['initialPreview'=>[
        Html::img($picture[0]->getImagePath(), ['class'=>'file-preview-image', 'alt'=>'Фото категории', 'title'=>'Фото категории']),
    ],
    'initialCaption' => "Картинка для мероприятия",
    ];
} else $pluginOpt = [];

if (isset($model->id_micropicture)){
    $pluginOpt2 = ['initialPreview'=>[
        Html::img($picture[1]->getImagePath(), ['class'=>'file-preview-image', 'alt'=>'Миниатюра категории', 'title'=>'Миниатюра категории']),
    ],
        'initialCaption' => "Картинка для мероприятия",
    ];
} else $pluginOpt2 = [];

?>

<div class="category-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <?=$form->field($picture[0], '[0]imageFile')->widget(FileInput::classname(), [
        'pluginOptions' => $pluginOpt1
    ])?>
    <?=$form->field($picture[1], '[1]imageFile')->widget(FileInput::classname(), [
        'pluginOptions' => $pluginOpt2
    ])->label('Миниатюра') ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <? $i=0;
    foreach($subcat as $onesubcat) {
        echo $form->field($onesubcat, "[$i]id_main")
            ->dropDownList($model->subcategoriesList(), ['prompt' => 'Без категории'])
            ->label("Категория");
        $i++;
    }
    ?>

    <?= $form->field($model, 'in_cloud')->checkbox() ?>
    <?= $form->field($model, 'priority') ?>
    <?= $form->field($model, 'main_priority') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>