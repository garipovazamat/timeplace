<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 18.04.2016
 * Time: 12:34
 *
 * @var $request \common\models\Request
 */

use yii\helpers\Url;

$user = $request->userFrom;
?>
<li class="tabs_item clearfix">
    <div class="avatar_tabs_item">
        <img src="<?=$user->getPhoto()->getBigMiniaturePath()?>" alt="">
    </div>
    <div class="center_tabs_item">
        <div class="name-uzer">
            <a href="#"><?=$user->allname?><span class="off-uzer <?=($user->isOnline()) ? 'on-uzer' : ''?>"></span></a>

        </div>
    </div>
    <div class="right_tabs_item">
        <a href="<?=$user->writeMessageUrl(true)?>" class="tabs_btn green_btn">Написать сообщение</a>
        <a href="<?=Url::to(['user/confirmrequest', 'id_request' => $request->id])?>" class="tabs_btn red_btn">
            Принять заявку
        </a>
        <a href="<?=Url::to(['user/refuserequest', 'id_request' => $request->id])?>" class="tabs_btn grey_btn">
            Отклонить заявку
        </a>
    </div>
</li>
