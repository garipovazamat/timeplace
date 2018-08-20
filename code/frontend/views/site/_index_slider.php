<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 23.03.2016
 * Time: 12:01
 *
 * @var $searchModel \frontend\models\SearchEvent
 */

use common\models\Headers;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\Event;
?>
<section class="slider">
    <div class="next_button"><img src="img/right-btn.png" alt=""></div>
    <div class="prev_button"><img src="img/left-btn.png" alt=""></div>
    <div class="slide">
        <!--  вставить изоражения-->
        <?
        $headers = Headers::getThisHeaders();
        foreach($headers as $one_header){
            ?>
            <div class="item">
                <img src="<?=$one_header->picture->getImagePath()?>"  alt="slider">
            </div>
            <?php if(count($headers) == 1){?>
                <div class="item">
                    <img src="<?=$one_header->picture->getImagePath()?>"  alt="slider">
                </div>
            <?php } ?>
        <?}?>
    </div>
    <!-- start block-TimePlace-->
    <div class="block-TimePlace">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="main-h1">TimePlace.me</h1>
                    <h3>Только актуальные события в твоем городе!</h3>
                    <!-- кнопки -->
                    <ul class="button-top">
                        <li>
                            <?php if(!Yii::$app->user->isGuest){ ?>
                            <a href="<?=Event::getCreateUrl()?>">
                                <?php } else {?>
                                <a class="open-popup" href="#login">
                                <?php } ?>
                                <span>Создать событие</span>
                            </a>
                        </li>
                        <li><a href="<?=Event::getIndexUrl()?>">
                                <span>Найти событие</span>
                            </a></li>
                        <li><a href="<?=Event::getMapUrl()?>">
                                <span>Карта событий</span>
                            </a></li>
                    </ul>
                    <!-- поиск-->
                    <div class="search">
                        <?php $form = ActiveForm::begin([
                            'action' => Url::to(['event/index']),
                            'method' => 'get',
                            'options' => ['class' => 'form-search']
                        ]); ?>
                            <div class="search-word">
                                <?= $form->field($searchModel, 'desc_search')
                                    ->input('text', ['class' => "input-search"])
                                    ->label(false) ?>
                            </div>
                            <div class="search-button">
                                <button class="btn-search">Поиск</button>
                            </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                    <!-- подсказка-->
                    <!--<div class="question">
                        <a class="open-popup" href="#head-help"><img src="img/question.png" alt="question"></a>
                    </div> !--->
                </div>
            </div>
        </div>
    </div>
    <!-- end block-TimePlace-->
</section>
