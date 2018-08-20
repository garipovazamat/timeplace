<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 17.04.2016
 * Time: 19:43
 * @var $friend \common\models\User
 */

use yii\helpers\Url;

$invite_event = Yii::$app->request->get('invite_event');
?>
<li class="tabs_item clearfix">
    <div class="avatar_tabs_item">
        <img src="<?=$friend->getPhoto()->getBigMiniaturePath()?>" alt="">
    </div>
    <div class="center_tabs_item">
        <div class="name-uzer">
            <a href="<?=$friend->getIndexUrl()?>">
                <?=$friend->allname?><span class="off-uzer <?=($friend->isOnline()) ? 'on-uzer' : ''?>"></span>
            </a>
        </div>
    </div>
    <div class="right_tabs_item">
        <a href="<?=$friend->writeMessageUrl(true)?>" class="tabs_btn green_btn">
            Написать сообщение
        </a>
        <a href="<?=Url::to(['user/deletefriend', 'id_friend' => $friend->id])?>" class="tabs_btn grey_btn">
            Убрать из друзей
        </a>
        <?php if(isset($invite_event)){ ?>
            <a href="<?=Url::to(['user/inviteuser', 'id_user' => $friend->id, 'id_event' => $invite_event])?>" class="tabs_btn grey_btn">
                Пригласить
            </a>
        <?php } ?>
    </div>
</li>
