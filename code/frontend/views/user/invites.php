<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 28.12.2015
 * Time: 10:51
 * @var $myInvites \common\models\Invite[]
 * @var $this \yii\web\View
 * @var $user \common\models\User;
 */

use yii\helpers\Html;

$user = Yii::$app->user->identity;
?>
    <header class="header_main_body">
        <div class="row">
            <div class="left-head col-sm-6">
                <div class="bread-crumbs">
                    <a href="<?= $user->getIndexUrl() ?>">Моя страница</a>
                </div>
                <h3>Приглашения</h3>
            </div>
            <div class="right-head col-sm-6">
                <?= $this->render('/layouts/_search_friend_form') ?>
            </div>
        </div>
    </header>
    <section class="main_section_main_body">
        <div class="col-md-4 col-md-push-8">
            <?= $this->render('/layouts/adversing') ?>
        </div>
        <div class="col-md-8 col-md-pull-4">
            <div class="tabs_list">
                <div class="wrap_tabs_content">
                    <ul class="tabs_content">
                        <?php
                        foreach($myInvites as $oneInvite){
                            echo $this->render('_one_invite_list', ['invite' => $oneInvite]);
                        }
                        ?>
                    </ul>
                </div>
            </div>
    </section>
