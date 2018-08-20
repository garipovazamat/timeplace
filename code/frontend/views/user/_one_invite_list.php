<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 24.04.2016
 * Time: 13:29
 * @var $invite \common\models\Invite
 */

use yii\helpers\Url;

$user = $invite->sender;
$event = $invite->event;
?>

<li class="tabs_item clearfix">
    <div class="avatar_tabs_item">
        <img src="<?=$user->getPhoto()->getBigMiniaturePath()?>" alt="">
    </div>
    <div class="center_tabs_item">
        <div class="name-uzer">
            <a href="<?=$user->getIndexUrl()?>">
                <?=$user->allname?>
            </a> приглашает на событие <br>
            <a href="<?$event->getEventUrl()?>"><?=$event->name?></a>
        </div>
    </div>
    <div class="right_tabs_item">
        <a href="<?=Url::to(['user/inviteconfirm', 'id' => $invite->id])?>" class="tabs_btn green_btn">
            Принять
        </a>
        <a href="<?=Url::to(['user/invitedelete', 'id' => $invite->id])?>" class="tabs_btn grey_btn">
            Отклонить
        </a>
    </div>
</li>