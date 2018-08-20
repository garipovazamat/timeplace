<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 28.11.2015
 * Time: 16:35
 * @var $this \yii\web\View
 * @var $iam User
 * @var $friends User[]
 * @var $requests Request[]
 */
use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Request;
use common\models\Event;

$iam = Yii::$app->user->identity;
?>
<header class="header_main_body">
    <div class="row">
        <div class="left-head col-sm-6">
            <div class="bread-crumbs">
                <a href="#">Моя страница</a>
            </div>
            <h3>Мои друзья</h3>
        </div>
        <div class="right-head col-sm-6">
            <form class="form-search">
                <div class="search-word">
                    <input type="text" class="input-search" placeholder="Искать людей">
                </div>
                <div class="search-button">
                    <button class="btn-search">Поиск</button>
                </div>
            </form>
        </div>
    </div>
</header>
<section class="main_section_main_body">
    <div class="col-md-4 col-md-push-8">
        <?=$this->render('/layouts/adversing')?>
    </div>
    <div class="col-md-8 col-md-pull-4">
        <div class="tabs">
            <ul class="tabs_controls clearfix">
                <li class=" tabs_controls-item active">
                    Мои друзья(<span><?=count($friends)?></span>)
                </li>
                <li class=" tabs_controls-item">
                    Входящие заявки(<span><?=count($requests)?></span>)
                </li>
            </ul>
            <div class="tabs_list">
                <div class="wrap_tabs_content">
                    <ul class="tabs_content">
                        <?php
                        foreach($friends as $oneFriend){
                            echo $this->render('_one_friend_list', ['friend' => $oneFriend]);
                        }
                        ?>
                    </ul>
                </div>
                <div class="wrap_tabs_content">
                    <ul class="tabs_content">
                        <?php
                        foreach($requests as $oneRequest){
                            echo $this->render('_one_request_list', ['request' => $oneRequest]);
                        }
                        ?>
                    </ul>
                </div>
            </div>
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