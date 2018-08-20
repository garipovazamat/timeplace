<?php
/**
 * @var $newComment \common\models\Comment
 * @var $allComments \common\models\Comment[]
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\models\User;

$user = Yii::$app->user->identity;
?>

<h3>Комментарии (<span><?=count($allComments)?></span>)</h3>
<ul class="comment-list">
<?php foreach($allComments as $oneComment){ ?>
    <li class="clearfix">
        <div class="uzer-avatar">
            <img src="<?=$oneComment->user->getPhoto()->getMiniaturePath()?>" alt="фото">
        </div>
        <div class="comment-uzer-wrap clearfix">
            <div class="name-uzer">
                <a href="#"><?=$oneComment->user->allname ?> <span class="off-uzer on-uzer"></span></a>
            </div>
            <div class="comment-uzer">
                <?=$oneComment->text?>
            </div>
            <div class="bottom-comment clearfix">
                <div class="date-comment"><span class="date">
                        <?=$oneComment->date_add?></span></div>
                <div class="comment-reply"><a href="#" class="reply">Ответить</a></div>
            </div>
        </div>
    </li>
<?php } ?>
</ul>