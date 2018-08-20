<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 17.04.2016
 * Time: 16:35
 * @var $event \common\models\Event
 */

?>
<li class="tabs_item clearfix">
    <div class="event_tabs_item">
        <img src="<?=$event->getBigMiniatureUrl()?>" alt="">
    </div>
    <div class="event-content_tabs_item">
        <div class="name-event_tabs">
            <a href="<?=$event->getUrl()?>"><?=$event->name?></a>
        </div>
        <p><?=$event->getShortDesc()?></p>
        <ul class="date-tabs">
            <li><?=$event->datetime ?></li>
            <li><?=$event->place?></li>
        </ul>
        <!--<ul class="button-event-tabs">
            <li><a href="#" class="btn red_btn">Пойду!</a></li>
        </ul>!-->
    </div>
</li>
