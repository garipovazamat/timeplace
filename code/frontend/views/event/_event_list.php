<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 10.04.2016
 * Time: 19:25
 *
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $searchModel \frontend\models\SearchEvent
 *
 * @var $events \common\models\Event[]
 */

$events = $dataProvider->getModels();

?>

<ul class="ev-grid row">
    <!-- start одно событие-->
    <?php foreach ($events as $oneEvent) { ?>
    <li class="col-sm-4 ev-grid-li">
        <article class="event">
            <!-- фото события-->
            <div class="event-img">
                <a href="#">
                    <img src="<?=$oneEvent->getLentaThumbnail()?>" alt="">
                </a>
            </div>
            <!-- заголовок события-->
            <h4><a href="<?=$oneEvent->getEventUrl()?>"><?=$oneEvent->name ?></a></h4>
            <ul class="event-uzer">
                <!-- название категории-->
                <li><a class="event-uzer-cat" href="#"><?=$oneEvent->getCat()->name?></a></li>
                <!-- имя создателя-->
                <li><span class="off-uzer on-uzer"></span><a href="<?=$oneEvent->user->getIndexUrl()?>">
                        <?=$oneEvent->user->allname?>
                    </a></li>
                <!-- кол-во людей-->
                <li><span class="people"><?=$oneEvent->getUserCount()?></span></li>
            </ul>
            <!-- сокращенное описание-->
            <p><?=$oneEvent->getShortDesc()?></p>
            <!-- вывод даты-->
            <ul class="date">
                <li><?=$oneEvent->datetime?></li>
                <li><?=$oneEvent->place?></li>
            </ul>
        </article>
    </li>
    <!-- end-->
    <?php } ?>
</ul>
