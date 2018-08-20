<?php
/**
 * @var $this \yii\web\View
 * @var $message common\models\Message;
 * @var $all_messages common\models\Message[];
 * @var $user User
 */
use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$iam = Yii::$app->user->identity;
?>
<header class="header_main_body">
    <div class="row">
        <div class="left-head col-sm-6">
            <div class="bread-crumbs">
                <a href="<?=Url::to(['messages/show'])?>">Мои сообщения</a>
            </div>
            <h3>Окно диалога</h3>
        </div>
        <!--<div class="right-head col-sm-6">
            <form class="form-search">
                <div class="search-word">
                    <input type="text" class="input-search" placeholder="Поиск сообщения">
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
        <div class="dialog-wrap">
            <div class="top-dialog">
                <div class="foto-uzer">
                    <img src="img/avatar.png" alt="">
                </div>
                <div class="name-uzer">
                    <a href="#"><?=$user->allname?> <span class="off-uzer on-uzer"></span></a>
                </div>
            </div>
            <ul class="dialog-list" id="message-text">
                <?php foreach($all_messages as $oneMessage){
                    if ($oneMessage->receiver == $iam->id) {
                        $oneMessage->reading = 1;
                        $oneMessage->save();
                    }
                    ?>
                <li class="dialog clearfix">
                    <a href="<?=Url::to(['messages/delete', 'id' => $oneMessage->id])?>" class="close-message">x</a>
                    <div class="foto-uzer">
                        <?= Html::img($oneMessage->sender0->getPhoto()->getMiniaturePath()) ?>
                    </div>
                    <div class="dialog-content-wrap">
                        <div class="top-dialog-content clearfix">
                            <div class="name-uzer">
                                <div>
                                    <a href="<?=$oneMessage->sender0->getIndexUrl()?>">
                                        <?=$oneMessage->sender0->allname ?><span class="off-uzer on-uzer"></span>
                                    </a>
                                </div>
                            </div>
                            <div class="date-time-wrap">
                                <?=$oneMessage->datetime?>
                            </div>
                        </div>
                        <div class="short-description">
                            <p><?=$oneMessage->text ?></p>
                        </div>
                    </div>
                </li>
                <?php } ?>
            </ul>
            <?php $form = ActiveForm::begin(['options' => [
                'data-pjax' => true,
                'class' => 'form-dialog',
            ]]); ?>

            <?= $form->field($message, 'text')->textarea([
                'rows' => 3,
                'class' => 'text-comment',
                'placeholder' => 'Комментировать...',
            ])->label(false) ?>
            <?= Html::submitButton('', ['class' => 'comment-btn']) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</section>

<script>
    window.onload = function()
    {
        var block = document.getElementById('message-text');
        block.scrollTop = 9999;
    }
</script>