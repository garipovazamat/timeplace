<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 27.12.2015
 * Time: 14:30
 * @var $events \common\models\Event[]
 * @var $iam \common\models\User
 * @var $this \yii\web\View
 */
use yii\helpers\Html;
use yii\helpers\Url;

$iam = Yii::$app->user->identity;
?>

<header class="header_main_body">
    <div class="row">
        <div class="left-head col-sm-8">
            <div class="bread-crumbs">
                <a href="<?=$iam->getIndexUrl()?>"><?=$iam->allname?></a>
            </div>
            <h3>События, которые создал <?=$iam->allname?></h3>
        </div>
        <!--<div class="right-head col-sm-4">
            <a href="#" class="subscription">Подписаться на События <span>Имя</span></a>
        </div>!-->
    </div>
</header>
<section class="main_section_main_body">
    <div class="col-md-4 col-md-push-8">
        <?=$this->render('/layouts/adversing')?>
    </div>
    <div class="col-md-8 col-md-pull-4">
        <div class="event-list">
            <ul class="tabs_content">
                <?php foreach($events as $oneEvent){
                    echo $this->render('_one_event_list', ['event' => $oneEvent]);
                } ?>
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
