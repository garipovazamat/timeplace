<?php

namespace common\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "request".
 *
 * @property integer $id
 * @property integer $id_from
 * @property integer $id_to
 * @property string $add_datetime
 * @property integer $confirm
 *
 * @property User $userFrom
 * @property User $userTo
 */
class Request extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'request';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_from', 'id_to', 'confirm'], 'integer'],
            [['add_datetime'], 'safe'],
            [['id_from'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_from' => 'id']],
            [['id_to'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_to' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_from' => 'Id From',
            'id_to' => 'Id To',
            'add_datetime' => 'Add Datetime',
            'confirm' => 'Confirm',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserFrom()
    {
        return $this->hasOne(User::className(), ['id' => 'id_from']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserTo()
    {
        return $this->hasOne(User::className(), ['id' => 'id_to']);
    }

    public function confirmButton(){
        return Html::a('Принять', ['user/confirmrequest', 'id_request' => $this->id], [
        'class' => 'btn btn-primary',
        ]);
    }

    public function refuseButton(){
        return Html::a('Отклонить', ['user/refuserequest', 'id_request' => $this->id], [
            'class' => 'btn btn-primary',
        ]);
    }

    /**
     * Подтверждение заявки
     */
    public function confirmRequest(){
        $friend1 = new Friend();
        $friend2 = new Friend();

        $friend1->id_from = $this->id_from;
        $friend1->id_to = $this->id_to;

        $friend2->id_from = $this->id_to;
        $friend2->id_to = $this->id_from;

        $this->confirm = 1;

        if($friend1->save() && $friend2->save())
            $this->delete();
    }
}
