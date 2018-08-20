<?php

namespace common\models;

use Yii;
use yii\bootstrap\Html;

/**
 * This is the model class for table "invite".
 *
 * @property integer $id
 * @property integer $id_user
 * @property integer $id_event
 * @property integer $confirm
 * @property integer $id_sender
 *
 * @property Event $event
 * @property User $user
 * @property User $sender
 */
class Invite extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invite';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'id_event', 'id_sender'], 'required'],
            [['id_user', 'id_event', 'id_sender'], 'integer'],
            [['id_event'], 'exist', 'skipOnError' => true, 'targetClass' => Event::className(), 'targetAttribute' => ['id_event' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
            [['id_sender'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_sender' => 'id']],
            ['confirm', 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'Id User',
            'id_event' => 'Id Event',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::className(), ['id' => 'id_event']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }

    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'id_sender']);
    }
    public function confirmInvite(){
        $this->confirm = 1;
        return $this->save();
    }

    public function getConfirmButton(){
        return Html::a('Принять', ['user/inviteconfirm', 'id' => $this->id],
            ['class' => 'btn btn-primary']);
    }
    public function getDeleteButton(){
        return Html::a('Отклонить', ['user/invitedelete', 'id' => $this->id],
            ['class' => 'btn btn-primary']);
    }
}
