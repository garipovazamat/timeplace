<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 20.04.2016
 * Time: 12:36
 */
use yii\widgets\ActiveForm;
use frontend\models\SearchUser;
use yii\helpers\Html;
use yii\helpers\Url;

$userSearch = new SearchUser();
?>

<?php $form = ActiveForm::begin([
    'method' => 'get',
    'action' => Url::to(['user/search']),
    'options' => [
        'id' => 'search-friends',
        'class' => 'form-search',
    ]
]); ?>
    <div class="search-word">
        <?=Html::activeInput('text', $userSearch, 'free_field', [
            'class' => 'input-search',
            'placeholder' => 'Искать людей',
        ]) ?>
    </div>
    <div class="search-button">
        <?=Html::submitButton('Поиск', ['class' => 'btn-search']) ?>
    </div>


<?php $form->end();?>