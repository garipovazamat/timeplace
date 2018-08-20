<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 23.03.2016
 * Time: 11:57
 *
 * @var $signupForm \frontend\models\SignupForm
 */
use yii\widgets\ActiveForm;
use common\models\City;
use common\models\Timezone;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Country;

?>

<div class="hidden">
    <?php $form = ActiveForm::begin([
        'id' => 'registration',
        'action' => Url::to(['site/signup']),
        'options' => [
            'class' => 'head-form registration',
        ]
    ]); ?>

<h3>Регистрация</h3>
<input type="hidden">
<fieldset>

    <a href="https://oauth.vk.com/authorize?client_id=5175774&scope=friends,email,photos&redirect_uri=<?=Url::toRoute(['site/loginvk'], true)?>">
        <img src="img/vk.png" alt="vk">Войти через VK
    </a>
    <a href="<?=$loginUrl?>">
        <img src="img/fb.png" alt="vk">Войти через Facebook
    </a>


    <?= Html::activeTextInput($signupForm, 'username', ['placeholder' => 'Имя..']); ?>
    <?= Html::activeTextInput($signupForm, 'sname', ['placeholder' => 'Фамилия..']); ?>
    <?= Html::activeTextInput($signupForm, 'email', ['placeholder' => 'E-mail..']); ?>
    <?=Html::dropDownList('country', null, Country::countryList(), ['class' => '',
        'onchange'=>'
                sendGet("'.Url::to(['site/selectcity']).'&id_country="+$(this).val(), function(data){
                    $("select#city_reg").html(data);
                    $("select#city_reg").trigger("chosen:updated");
                });
            '
    ]);?>
    <?= $form->field($signupForm, 'idCity')
        ->dropDownList(City::cityList(), [
            'class' => 'choose_city',
            'id' => 'city_reg',
            'prompt' => 'Не выбрано'
        ]) ?>
    <?//= $form->field($signupForm, 'dateBorn') ?>
    <?= $form->field($signupForm, 'sex')->dropDownList(['Мужской', 'Женский'], ['class' => '']) ?>
    <br>
    <?= Html::activeTextInput($signupForm, 'password', ['placeholder' => 'Пароль..']); ?>
    <?= $form->field($signupForm, 'tz_val')->dropDownList(Timezone::timezoneList(), ['class' => '']) ?>

    <p>Регистрируясь, вы соглашаетесь с <span class="activ-box">политикой конфидициальности</span></p>
    <div class="hid-box">
        <p>Сайт уважает ваше право и соблюдает конфиденциальность при заполнении, передачи и хранении ваших конфиденциальных сведений. Регистрация на сайте означает ваше согласие на обработку данных.</p>
        <p>Под персональными данными подразумевается информация, относящаяся к субъекту персональных данных, в частности фамилия, имя и отчество, дата рождения, адрес, контактные реквизиты (телефон, адрес электронной почты), семейное, имущественное положение и иные данные, относимые Федеральным законом от 27 июля 2006 года № 152-ФЗ «О персональных данных» (далее – «Закон») к категории персональных данных. Целью обработки персональных данных является оказание сайтом услуг.</p>
        <p>В случае отзыва согласия на обработку своих персональных данных мы обязуемся удалить Ваши персональные данные в срок не позднее 3 рабочих дней. Отзыв согласия можно отправить в электронном виде на наш электронный адрес.</p>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Регистрация', ['class' => 'btn-form']) ?>
    </div>
</fieldset>
<?php ActiveForm::end(); ?>
</div>