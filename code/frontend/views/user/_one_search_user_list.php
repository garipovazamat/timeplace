<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 20.04.2016
 * Time: 18:15
 * @var $user \common\models\User
 */
use yii\helpers\Url;
?>
<li class="tabs_item clearfix">
    <div class="avatar_tabs_item">
        <img src="<?=$user->getPhoto()->getBigMiniaturePath()?>" alt="">
    </div>
    <div class="center_tabs_item">
        <div class="name-uzer">
            <a href="<?=$user->getIndexUrl()?>"><?=$user->allname?><span class="off-uzer <?=($user->isOnline()) ? 'on-uzer' : ''?>"></span></a>
            <span class="uzer-city"><?=$user->getCityName()?></span>
        </div>
    </div>
    <div class="right_tabs_item">
        <?php if($user->isFriend()){?>
        <a href="<?=$user->writeMessageUrl(true)?>" class="tabs_btn green_btn">Написать сообщение</a>
        <?php } ?>
        <?php if(!($user->isFriend() || $user->isSendRequest())){ ?>
            <a href="<?=Url::to(['user/addfriend', 'id' => $user->id])?>" class="tabs_btn red_btn">Добавить в друзья</a>
        <?php } ?>
    </div>
    <!--<a href="#" class="subscription">Подписаться на События</a>!-->
</li>
