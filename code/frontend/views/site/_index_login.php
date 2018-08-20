<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 23.03.2016
 * Time: 12:34
 *
 * @var $loginForm \common\models\LoginForm
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<!-- форма входа-->
<div class="hidden">
    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'login', 'class' => 'head-form'],
        'action' => Url::to(['site/login']),
    ]); ?>
        <h3>Вход</h3>
        <fieldset>
            <?= Html::activeTextInput($loginForm, 'email', ['placeholder' => 'Email..']); ?>
            <?= Html::activeTextInput($loginForm, 'password', ['placeholder' => 'Пароль..']); ?>
            <?= Html::activeCheckbox($loginForm, 'rememberMe', [
                'class' => 'checkbox',
                'placeholder' => 'Пароль..'
            ]); ?>

            <div>
                Если вы забыли пароль, то можете его
                <a href="<?=Url::to(['site/request-password-reset'])?>" style="display: inline">
                    восстановить
                </a>.
            </div>
            <?= Html::submitButton('Войти', ['class' => 'btn-form']) ?>
            <a href="https://oauth.vk.com/authorize?client_id=5175774&scope=friends,email,photos&redirect_uri=<?=Url::toRoute(['site/loginvk'], true)?>">
                <img src="img/vk.png" alt="vk">Войти через VK
            </a>
            <a href="<?=$loginUrl?>">
                <img src="img/fb.png" alt="vk">Войти через Facebook
            </a>
        </fieldset>
    </form>
</div>
