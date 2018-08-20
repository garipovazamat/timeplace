<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Headers */

$this->title = 'Create Headers';
$this->params['breadcrumbs'][] = ['label' => 'Headers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="headers-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'picture' => $picture
    ]) ?>

</div>
