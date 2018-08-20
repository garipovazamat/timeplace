<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 23.03.2016
 * Time: 11:54
 */

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\Country;
use yii\helpers\Html;
use common\models\City;

$city = City::getSessionCity();
?>
<div class="hidden">
    <?php
    $form = ActiveForm::begin([
        'action' => Url::to(['site/setcity']),
        'method' => 'post',
        'options' =>[
            'id' => 'select-city',
            'class' => 'head-form',
        ]
    ]);
    ?>
<h3>Выбор города</h3>
<input type="hidden">
<fieldset>
    <div class="select-wrap">
        <?=Html::dropDownList('country', null, Country::countryList(), ['class' => '',
            'onchange'=>'
                sendGet("'.Url::to(['site/selectcity']).'&id_country="+$(this).val(), function(data){
                    $("select#city_choose").html(data);
                    $("select#city_choose").trigger("chosen:updated");
                });
            '
        ]);?>
        <?= $form->field($city, 'id_city')
            ->dropDownList(City::cityList(), [
                'class' => 'choose_city',
                'id' => 'city_choose',
                'prompt' => 'Не выбрано'
            ]) ?>
    </div>
    <?=Html::submitButton('Выбрать', ['class' => 'btn-form',])?>
</fieldset>
<?php
ActiveForm::end();
?>
</div>