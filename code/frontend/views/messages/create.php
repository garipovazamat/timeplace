<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 07.01.2016
 * Time: 21:27
 * @var \common\models\Message $message
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<div class="create_message">
    <?
    $user = Yii::$app->user->identity;
    $form = ActiveForm::begin();
    echo $form->field($message, 'receiver')->dropDownList($user->getFriendsList());
    echo $form->field($message, 'text')->textarea(['rows' => 3])->label(false);
    echo Html::submitButton('Написать', ['class' => 'mysubmit']);
    ?>
</div>
