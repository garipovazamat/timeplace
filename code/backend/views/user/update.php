<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $user_role app\models\Roles */
/* @var $photo common\models\Picture */

$this->title = 'Редактирование пользователя: ' . ' ' . $model->username . ' ' . $model->sname;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username . ' ' . $model->sname, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';


?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'user_role' => $user_role,
        'photo' => $photo
    ]) ?>

</div>
