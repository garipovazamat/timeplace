<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 23.11.2015
 * Time: 15:41
 */
/* @var $searchModel \frontend\models\SearchUser */
/* @var $dataProvider yii\data\ActiveDataProvider */
/**
 * @var $this \yii\web\View
 */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\City;
use yii\grid\DataColumn;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\models\Country;
use common\models\User;

$this->registerJsFile('https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js', ['position' => \yii\web\View::POS_HEAD]);
$users = $dataProvider->getModels();
?>

<header class="header_main_body">
    <div class="row">
        <div class="left-head col-sm-6">
            <div class="bread-crumbs">
                <a href="#">Моя страница</a>
            </div>
            <h3>Поиск людей (<span><?=$dataProvider->totalCount?></span>)</h3>
        </div>
        <div class="right-head col-sm-6">
            <?=$this->render('/layouts/_search_friend_form') ?>
        </div>
    </div>
</header>
<section class="main_section_main_body">
    <div class="col-md-4 col-md-push-8">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'options' => ['data-pjax' => true, 'class' => 'search-people-form'],
        ]); ?>
        <div class="form-group">
            <label class="control-label" for="city">Страна</label>
            <?= Html::dropDownList('country', null, Country::countryList(), ['id' => 'country',
                'class' => 'form-control modif_country',
                'onchange'=>'
                                    $.post( "'.Url::to(['site/selectcity']).'&id_country="+$(this).val(), function(data) {
                                    $("select#city").html(data);
                                    $("select#city").trigger("chosen:updated");
                                    });',
                'template' => '<div class="select-wrap">{label}{input}</div>',
            ]); ?>
        </div>
        <?= $form->field($searchModel, 'id_city')->
        dropDownList(City::cityList(), ['id' => 'city']); ?>
            <div class="select-wrap">
                <label for="clan_search-people" class="control-label">Пол</label>
                <?=Html::activeDropDownList($searchModel, 'sex', User::getSexList(), [
                    'prompt' => 'Не выбрано',
                    'id' => 'clan_search-people'
                ]) ?>
            </div>
            <!--<div class="select-wrap clearfix">
                <label for="from_age_search-people" class="control-label">Возраст</label>
                <div  class="wrap-two-select">
                    <select name="from_age_search-people" id="from_age_search-people">
                        <option value="0">от</option>
                        <option value="1">18</option>
                        <option value="2">19</option>
                    </select>
                    <select name="till_age_search-people" id="till_age_search-people">
                        <option value="0">до</option>
                        <option value="1">18</option>
                        <option value="2">19</option>
                    </select>
                    <div class="center-two-select"><span>&mdash;</span></div>
                </div>
            </div>!-->
        <?=Html::submitButton('Найти')?>
        <?php ActiveForm::end() ?>
        <?=$this->render('/layouts/adversing')?>
    </div>
    <div class="col-md-8 col-md-pull-4">
        <div class="search-people">
            <ul class="tabs_content">
                <?php
                foreach($users as $oneUser){
                    echo $this->render('_one_search_user_list', ['user' => $oneUser]);
                }
                ?>
            </ul>
        </div>
    </div>
    <?=$this->render('/layouts/paginator', [
        'paginator' => $dataProvider->pagination,
        'type' => 1
    ])?>
</section>


<script>
    $('#searchuser-id_city').chosen({no_results_text:'Нет результатов поиска'});
</script>
