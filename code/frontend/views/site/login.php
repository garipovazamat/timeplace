<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
Yii::$app->session->open();
$fb = new Facebook\Facebook([
    'app_id' => '102474660129796',
    'app_secret' => '31df04a597d8ae6893c6abfb01a9eb0c',
    'default_graph_version' => 'v2.2',
]);
$helper = $fb->getRedirectLoginHelper();
$permissions = ['user_about_me', 'user_birthday', 'user_location', 'email'];
$loginUrl = $helper->getLoginUrl(Url::toRoute(['site/loginfacebook'], true), $permissions);

?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'email') ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div>
                    Если вы забыли пароль, то можете его
                    <a href="<?=Url::to(['site/request-password-reset'])?>" style="display: inline">
                        восстановить
                    </a>.
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    <br><br>
                    <?= Html::a('<img src="/images/vkicon.png">Войти через ВК',
                        'https://oauth.vk.com/authorize?client_id=5175774&scope=friends,email,photos&redirect_uri=' . Url::toRoute(['site/loginvk'], true),
                        ['class' => 'btn btn-default'])?>
                    <br><br>
                    <?= Html::a('<img src="/images/facebook.png">Войти через Facebook', $loginUrl,
                        ['class' => 'btn btn-default'])?>
                </div>

            <?php ActiveForm::end(); ?>
            <?= Html::a('Регистрация', ['site/signup'], ['class' => 'btn btn-default'])?>
        </div>
    </div>
</div>
