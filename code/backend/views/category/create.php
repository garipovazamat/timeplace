<?php

use yii\helpers\Html;
use common\models\Category;


/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $picture common\models\Picture; */
/* @var $subcat common\models\Subcategory; */
/* @var $microPicture common\models\Picture; */

$this->title = 'Создание категории';
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'subcat' => $subcat,
        'picture' => $picture,
    ]) ?>

</div>
