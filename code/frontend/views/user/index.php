<?php
/**
 * @var $model \common\models\User
 * @var $this \yii\web\View
 */
use yii\helpers\Url;
?>

<header class="header_main_body">
    <div class="row">
        <div class="left-head col-sm-6">
            <div class="bread-crumbs">
            </div>
            <h3><?=$model->allname ?> <span class="off-uzer on-uzer"></span></h3>
        </div>
        <div class="right-head col-sm-6">
        </div>
    </div>
</header>
<section class="main_section_main_body uzer-page">
    <div class="col-md-4 col-md-push-8">
        <?=$this->render('/layouts/adversing')?>
    </div>
    <div class="col-md-8 col-md-pull-4">
        <div class="uzer-page-img">
            <img src="<?=$model->getPhoto()->getImagePath()?>" alt="">
            <?php if(!empty($model->id_vk) || (!empty($model->pagevk))){?>
                <a href="<?=$model->getVkUrl()?>" class="vk" target="_blank"></a>
            <?php } ?>
        </div>
        <?php if(!Yii::$app->user->isGuest)
            if(Yii::$app->user->id != $model->id){ ?>
        <div class="btn-uzer-page">
            <a href="<?=$model->writeMessageUrl(true)?>" class="btn green_btn">Написать сообщение</a>
            <?php if($model->isFriend()){?>
                <a href="<?=Url::to(['user/deletefriend', 'id_friend' => $model->id])?>" class="btn grey_btn">
                    Убрать из друзей
                </a>
            <?php } elseif(!$model->isSendRequest()){?>
                <a href="<?=Url::to(['user/addfriend', 'id' => $model->id])?>" class="btn grey_btn">
                    Добавить в друзья
                </a>
            <?php } else{?>
                <a href="#" class="btn grey_btn">
                    Заявка отправлена
                </a>
            <?php } ?>
        </div>
        <?php } ?>
        <div class="about_uzer-page">
            <h4>О себе:</h4>
            <div class="content-about">
                <ul>
                    <li><b>Возраст:</b>
                        <span class="age_about_uzer"><?=$model->getAge()?></span>
                    </li>
                    <li><b>Город:</b>
                        <span class="city_about_uzer"><?=$model->showCityName()?></span>
                    </li>
                </ul>
                <p><?=$model->aboutme ?></p>
            </div>
        </div>

        <?php
        $gone_events = $model->getParticipateEvents(false);
        $participted_events = $model->getParticipateEvents(true);
        $self_events = $model->getMyEvents(true);
        ?>
        <div class="tabs">
            <ul class="tabs_controls clearfix row">
                <li class="tabs_controls-item active col-md-4">
                    Собираюсь пойти
                </li>
                <li class="tabs_controls-item col-md-4">
                    Участвовал в событиях
                </li>
                <li class="tabs_controls-item col-md-4">
                    Создал события
                </li>
            </ul>
            <div class="tabs_list">
                <div class="wrap_tabs_content">
                    <!--Собираюсь пойти!-->
                    <ul class="tabs_content">
                        <?php foreach($gone_events as $oneEvent){?>
                            <li class="tabs_item clearfix">
                                <?=$this->render('_one_event_list', ['event' => $oneEvent]);?>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="wrap_tabs_content">
                    <!--Участвовал в событиях!-->
                    <ul class="tabs_content">
                        <?php foreach($participted_events as $oneEvent){?>
                            <li class="tabs_item clearfix">
                                <?=$this->render('_one_event_list', ['event' => $oneEvent]);?>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="wrap_tabs_content">
                    <!--Создал события!-->
                    <ul class="tabs_content">
                        <?php foreach($self_events as $oneEvent){?>
                            <li class="tabs_item clearfix">
                                <?=$this->render('_one_event_list', ['event' => $oneEvent]);?>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>