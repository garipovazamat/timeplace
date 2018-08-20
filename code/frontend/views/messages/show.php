<?php
/* @var $this yii\web\View */
use common\models\Message;
use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $send_users User[];
 * @var $user User
 */
$user =  Yii::$app->user->identity;
$myid = $user->getId();

$send_users = User::find()//->select("user.*, MAX(datetime)")
    ->joinWith('message')
    //->where('message.id is not null')
    ->andWhere("receiver = $myid")
    ->groupBy('user.id')
    ->having('COUNT(message.id) > 0')
    ->orderBy('MAX(datetime) DESC')
    ->all();
?>

<header class="header_main_body">
    <div class="row">
        <div class="left-head col-sm-6">
            <div class="bread-crumbs">
                <a href="<?=$user->getIndexUrl()?>"><?=$user->allname?></a>
            </div>
            <h3>Мои сообщения</h3>
        </div>
        <!--<div class="right-head col-sm-6">
            <form class="form-search">
                <div class="search-word">
                    <input type="text" class="input-search" placeholder="Поиск собеседника">
                </div>
                <div class="search-button">
                    <button class="btn-search">Поиск</button>
                </div>
            </form>
        </div>!-->
    </div>
</header>
<section class="main_section_main_body">
    <div class="col-md-4 col-md-push-8">
        <?=$this->render('/layouts/adversing')?>
    </div>
    <div class="col-md-8 col-md-pull-4">
        <div class="my-message">
            <ul class="tabs_content">
                <?php
                foreach ($send_users as $oneUser){
                    $oneUserId = $oneUser->id;
                    $new_mess_count = Message::find()
                        ->where(['receiver' => $myid, 'sender' => $oneUser->id, 'reading' => 0])
                        ->count();
                    ?>
                <li class="tabs_item <?=($new_mess_count > 0)? 'new-message' : ''?> clearfix">
                    <a href="<?=Url::to(['messages/write', 'id' => $oneUser->id])?>" class="link-message clearfix">
                        <?php if($new_mess_count > 0){?>
                            <span class="num-aftor"><?=$new_mess_count?></span>
                        <?php } ?>
                        <div class="avatar_tabs_item">
                            <img src="<?=$oneUser->getPhoto()->getBigMiniaturePath()?>" alt="">
                        </div>
                        <div class="center_tabs_item">
                            <div class="name-uzer">
                                <div><?=$oneUser->allname?>
                                    <?php if($oneUser->isOnline()){ ?>
                                    <span class="off-uzer on-uzer"></span>
                                    <? } ?>
                                </div>
                            </div>
                            <div class="short-description">
                                <p><?=$oneUser->getLastMessage()->getShortText()?></p>
                            </div>
                        </div>
                        <div class="right_tabs_item">
                            <span class="date">29 февр 2016</span>
                            <span class="time">10:00</span>
                        </div>
                    </a>
                </li>
                <?php } ?>
            </ul>
        </div>
        <!--<div class="pagination-wrap">
            <ul class="pagination">
                <li class="prev-page disabled"><a href="#">&lt;</a></li>
                <li class="prev-page disabled"><a href="#">Назад</a></li>
                <li class="active"><a href="#">1</a></li>
                <li><a class="number" href="#">2</a></li>
                <li><a class="number" href="#">3</a></li>
                <li class="gap"><span>...</span></li>
                <li><a class="number" href="#">50</a></li>
                <li class="next-page"><a href="#">Вперед</a></li>
                <li class="next-page"><a href="#">&gt;</a></li>
            </ul>
        </div>!-->
    </div>
</section>

