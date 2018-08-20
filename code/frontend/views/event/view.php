<?php

/**
 * @var $model \common\models\Event
 * @var $this \yii\web\View
 * @var $members User[]
 */
use common\models\User;
use common\models\Comment;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyA4t1iQVtgKE7qSaPI6xmtlIvHZzs34_DY&callback=initMap&libraries=places');
$this->registerJsFile('/js/timer.js', ['position' => yii\web\View::POS_HEAD]);
$pictures = $model->getPictures();
$this->title = $model->name;
?>
<header class="header_main_body">
    <div class="row">
        <div class="left-head col-sm-8">
            <div class="bread-crumbs">
                <a href="<?=$model->getThisCat()->getCatUrl()?>"><?=$model->getThisCat()->name?></a>
            </div>
            <h3><?=$model->name?>
                <?=($model->isOwner()) ? Html::a('[Редактировать] [Копировать]', $model->getUpdateUrl(), ['style' => "color: #FC7642"]) : ''?>
        </div>
        <div class="right-head col-sm-4">
            <!--<a href="#" class="subscription">Подписаться на подкатегорию</a>!-->
        </div>
    </div>
</header>
<section class="main_section_main_body">
    <div class="col-md-4 col-md-push-8">
        <div class="info-event">
            <div class="type-event-wrap">
                <span class="type-event">
                    <?=($model->opened) ? 'Открытое событие' : 'Закрытое событие'?>
                </span>
                <div>Поделиться событием:</div>
                <script src="https://yastatic.net/share2/share.js" async="async"></script>
                <div class="ya-share2 clearfix" data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter">

                </div>
                <div class="info-event-button-group clearfix">
                    <?=$model->getRunButton() ?>
                    <?=$model->getLikeButton() ?>
                </div>
                <?=$model->getInviteButton() ?>
            </div>
            <div class="countdown">
                <? if(!$model->isStart()){?>
                    <h4>До начала события осталось</h4>
                <?php } if($model->isNotEndAndStart()) { ?>
                    <h4>До конца события осталось</h4>
                <?}?>
                <div class="clock">
                    <div class="mytimer_container">
                        <div id="mytimer"></div>
                    </div>
                </div>
            </div>
            <div class="org">
                <h4>Организатор</h4>
                <ul class="link-uzer-wrap-info-uzer clearfix">
                    <li class="link-uzer-info-uzer col-xs-4">
                        <a href="<?=$model->user->getIndexUrl()?>">
                            <div class="uzer-avatar">
                                <img src="<?=$model->user->getPhoto()->getMiniaturePath()?>" alt="фото">
                            </div>
                            <?=$model->user->allname?>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="participant">
                <h4>Участники</h4>
                <ul class="link-uzer-wrap-info-uzer clearfix">
                    <?php
                    $members = $model->getMembers();
                    foreach($members as $one_member){?>
                    <li class="link-uzer-info-uzer col-xs-4">
                        <a href="<?=$one_member->getIndexUrl()?>">
                            <div class="uzer-avatar">
                                <img src="<?=$one_member->getPhoto()->getMiniaturePath()?>" alt="фото">
                            </div>
                            <?=$one_member->allname?>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <div class="advertising">
            <p>Место под рекламу</p>
        </div>
    </div>
    <div class="col-md-8 col-md-pull-4">
        <div class="event-page">
            <div class="description-event">
                <?=$model->fulldesc ?>
            </div>
            <div class="slider-event">
                <div class="next_button"><img src="img/right-btn-event.png" alt=""></div>
                <div class="prev_button"><img src="img/left-btn-event.png" alt=""></div>
                <div class="slide">
                    <!--  вставить изоражения-->
                    <?php foreach($pictures as $onePicture){ ?>
                        <div class="item">
                            <img src="<?=$onePicture->getImagePath()?>"  alt="...">
                        </div>
                        <?php if(count($pictures) == 1){?>
                            <div class="item">
                                <img src="<?=$onePicture->getImagePath()?>"  alt="...">
                            </div>
                            <?php } ?>
                    <?php } ?>
                </div>
            <ul class="description_list">
                <li class="clearfix"><div class="left_dl"><span>Начало</span></div><div class="right_dl">
                        <?=User::getMyDatetime($model->datetime)?>
                    </div></li>
                <?php if(!empty($model->datetime_to)) {?>
                    <li class="clearfix"><div class="left_dl"><span>Окончание</span></div><div class="right_dl">
                        <?=$model->datetime_to ?>
                    </div></li>
                <?php } ?>
                    <li class="clearfix"><div class="left_dl"><span>Город</span></div><div class="right_dl">
                        <?=$model->city->name ?>
                    </div></li>
                <li class="clearfix"><div class="left_dl"><span>Место</span></div><div class="right_dl">
                        <?=$model->place ?>
                    </div></li>
                <?php if(!empty($model->cost)) {?>
                    <li class="clearfix"><div class="left_dl"><span>Стоимость</span></div><div class="right_dl">
                        <?=$model->cost ?>
                    </div></li>
                <?php } ?>
            </ul>
                <?php if(!empty($model->coordinates)){ ?>
                <div class="event-map" id="map">
                </div>
                <?php } ?>
            <div class="row share-event-wrap">
                <div class="col-sm-6 share-event">Поделиться событием:</div>
                <script src="https://yastatic.net/share2/share.js" async="async"></script>
                <div class="col-sm-6 clearfix">
                    <div class="ya-share2 clearfix"
                         data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter">
                    </div>
                </div>
            </div>
            <div class="comment-wrap">
                <?php
                $newComment = new Comment();

                if($newComment->load(Yii::$app->request->post())){
                    $newComment->text = htmlspecialchars($newComment->text);
                    $newComment->text = nl2br($newComment->text);
                    $newComment->id_event = $model->id;
                    $newComment->id_user = Yii::$app->user->id;
                    $newComment->date_add = date('Y-m-d H:i:s');
                    $newComment->save();
                    unset($_POST);
                }
                $allComments = $model->findAllComments();
                echo $this->render('_comments', [
                    'newComment' => $newComment,
                    'allComments' => $allComments,
                ]);
                ?>
                <?php $form = ActiveForm::begin([
                    'method' => 'post',
                    'options' => ['class' => 'form-comment']
                ]); ?>
                <?= $form->field($newComment, 'text')
                    ->textarea(['rows' => 2, 'class' => 'text-comment', 'placeholder' => 'Комментировать...'])
                    ->label(false) ?>
                <?=Html::submitButton('', ['class' => 'comment-btn'])?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</section>

<?php if(!empty($model->coordinates)){ ?>
<script>
    var map;
    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            <?php
             $coords = explode(',', $model->coordinates);
             ?>
            center: {lat: <?=$coords[1]?>, lng: <?=$coords[0]?>},
            zoom: 12
        });
        var marker = new google.maps.Marker({
            position: {lat: <?=$coords[1]?>, lng: <?=$coords[0]?>},
            map: map
        });
        marker.setMap(map);
    }
</script>
<?php } ?>

<?php
if(!$model->isStart())
    $time = strtotime(User::getMyDatetime($model->datetime));
if($model->isNotEndAndStart())
    $time = strtotime(User::getMyDatetime($model->datetime_to));
$s = date('s', $time);
$i = date('i', $time);
$H = date('H', $time);
$d = date('d', $time);
$m = date('m', $time);
$Y = date('Y', $time);
$params = "$s, $i, $H, $d, $m, $Y";
?>


<script type="text/javascript">
    <?php if(!$model->isStart() || $model->isNotEndAndStart()){?>
    countDown(<?=$params?>);
    <?}?>
</script>