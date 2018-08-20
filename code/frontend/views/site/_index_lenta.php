<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 23.03.2016
 * Time: 11:51
 *
 * @var $lentaEvents \common\models\Event[]
 */

use yii\helpers\Url;

?>
<section id="event" class="event-wrap">
    <div class="container">
        <h2>Ближайшие по времени события</h2>
        <!-- start фильтр по дате-->
        <!-- конец формы-->
        <ul class="ev-grid row">
            <!-- start вывод событий-->
            <? foreach($lentaEvents as $oneEvent){
                ?>
                <!-- start одно событие-->
                <li class="col-sm-4 ev-grid-li">
                    <article class="event">
                        <!-- фото события-->
                        <div class="event-img">
                            <a href="<?=$oneEvent->getUrl()?>">
                                <img src="<?=$oneEvent->getLentaThumbnail()?>" alt="">
                            </a>
                        </div>
                        <!-- заголовок события-->
                        <h4>
                            <a href="<?=$oneEvent->getUrl()?>">
                                <?=$oneEvent->name?>
                            </a></h4>
                        <ul class="event-uzer">
                            <!-- название категории-->
                            <li><a class="event-uzer-cat" href="<?=Url::to(['event/index', 'category' => $oneEvent->getCat()->id])?>">
                                    <?=$oneEvent->getCat()->name?>
                                </a></li>
                            <!-- имя создателя-->
                            <li><span class="off-uzer on-uzer"></span>
                                <a href="<?=Url::to(['user/index', 'id' => $oneEvent->user_id])?>">
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
            <?}?>
            <!-- end-->
        </ul>
    </div>
</section>
