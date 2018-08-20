<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "event_user".
 *
 * @property integer $id
 * @property integer $id_user
 * @property integer $id_event
 * @property string $date_entry
 *
 * @property User $idUser
 * @property Event $idEvent
 */
class EventUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'id_event'], 'required'],
            [['id_user', 'id_event'], 'integer'],
            [['date_entry'], 'safe'],
            [['id_user', 'id_event'], 'unique', 'targetAttribute' => ['id_user', 'id_event']]
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
            'date_entry' => 'Date Entry',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::className(), ['id' => 'id_event']);
    }

    /**
     * @param $invite Invite
     */
    public static function participate($invite){
        $event_user = new EventUser();
        $event_user->id_user = $invite->id_user;
        $event_user->id_event = $invite->id_event;
        $event_user->save();
    }

}
