<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Event */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Мероприятия', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Посмотреть страницу мероприятия',
            'http://timeplace.me' . Url::to(['event/view', 'id' => $model->id]),
            ['class' => 'btn btn-primary', 'target' => '_blank']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?
    function getModeratedButton($model){
        if($model->moderated)
            return '<a href="'.Url::to(["event/moderateevent", "id" => $model->id]).'"><span class="glyphicon glyphicon-thumbs-up", style="color: green; font-size: 40px"></span></a>';
        else return '<a href="'.Url::to(["event/moderateevent", "id" => $model->id]).'"><span class="glyphicon glyphicon-thumbs-down", style="color: red; font-size: 40px"></span></a>';
    }
    ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            ['label' => 'Создатель', 'value' => $model->user->username . ' ' . $model->user->sname . ' <'.$model->user->email.'>'],
            'name',
            'fulldesc:ntext',
            'datetime',
            'datetime_to',
            'place',
            'city.name',
            [
                'label' => 'Модерация',
                'format' => 'html',
                'value' => getModeratedButton($model)
            ],
        ],
    ]) ?>

</div>
