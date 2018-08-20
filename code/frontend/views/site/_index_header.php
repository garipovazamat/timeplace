<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 10.04.2016
 * Time: 17:18
 * @var $user \common\models\User
 */

use yii\helpers\Url;
use common\models\Message;
use common\models\City;

if(Yii::$app->session->has('id_city')) {
    $city = City::getSessionCity();
    $cityName = $city->name;
} else $cityName = 'Выберите город';
?>

<div class="container">
    <div class="row">
        <!-- start лого и выбор города-->
        <div class="col-sm-6 logo-city">
            <a class="logo" href="/"><img src="img/logo.png" height="57" width="57" alt="TimePlace"></a>
            <a class="city open-popup" href="#select-city"><?=$cityName?></a>
        </div>
        <!-- end-->

        <div class="col-sm-6 right-nav">
            <?php if(Yii::$app->user->isGuest) {?>
                <!-- start блог регистрации и входа-->
                <div class="guest">
                    <a class="reg open-popup" href="#registration">Регистрация</a>
                    <a class="login open-popup" href="#login">Вход</a>
                </div>
            <?php } else {
                $user = Yii::$app->user->identity;
                ?>
                <!-- start правая часть авторизованного пользователя -->
                <div class="aftor clearfix">
                    <div class="aftor-mnu">
                        <a href="#" class="toggle-menu clearfix"><span></span></a>
                    </div>
                    <!-- end -->
                    <!-- start вывод пользователя-->
						<span class="my-page">
							<a href="<?=Url::to(['user/index'])?>">
                                <?=$user->allname?>
                            </a>
						</span>
                    <!-- start аватар пользователя-->
                    <div class="aftor-avatar">
                        <img src="<?=$user->getPhoto()->getMiniaturePath()?>" alt="">
                    </div>
                    <!-- start входящие сообщения-->
						<span class="message">
							<a href="<?=Url::to(['messages/show'])?>">
                                <span class="num-aftor"><?=Message::getNewMessCount()?></span>
                            </a>
						</span>
                </div>
                <!-- start меню в бургере-->
                <ul class="menu-aftor">
                    <li><a href="<?=Url::to(['user/index'])?>">Моя Страница</a></li>
                    <li><a href="<?=Url::to(['messages/show'])?>">
                            Сообщения <span class="num-aftor"><?=Message::getNewMessCount()?></span>
                        </a></li>
                    <li><a href="<?=$user->getMyEventsUrl()?>">Мои события</a></li>
                    <li><a href="<?=Url::to(['user/invites'])?>">Приглашения</a></li>
                    <li><a href="<?=Url::to(['user/friends'])?>">Друзья</a></li>
                    <li><a href="<?=Url::to(['user/edit'])?>">Настройки</a></li>
                    <li><a href="<?=Url::to(['/site/logout'])?>">Выход</a></li>
                    <li><a class="new-event" href="<?=Url::to(['event/create'])?>">
                            Создать событие
                        </a></li>
                </ul>
            <?php } ?>
            <!-- end-->
        </div>
    </div>
</div>
