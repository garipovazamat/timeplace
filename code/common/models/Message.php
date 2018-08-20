<?php

namespace common\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "message".
 *
 * @property integer $sender
 * @property integer $receiver
 * @property string $datetime
 * @property integer $reading
 * @property string $text
 * @property integer $id
 *
 * @property User $receiver0
 * @property User $sender0
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sender', 'receiver', 'reading'], 'integer'],
            [['datetime'], 'safe'],
            [['text'], 'string'],
            [['receiver'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['receiver' => 'id']],
            [['sender'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['sender' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sender' => 'Отправитель',
            'receiver' => 'Получатель',
            'datetime' => 'Время отправки',
            'reading' => 'Прочтенность',
            'text' => 'Текст',
            'id' => 'ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiver0()
    {
        return $this->hasOne(User::className(), ['id' => 'receiver']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender0()
    {
        return $this->hasOne(User::className(), ['id' => 'sender']);
    }

    public static function sendMessage($sender_id, $receiver_id, $text){
        $message = new Message();
        $message->sender = $sender_id;
        $message->receiver = $receiver_id;
        $message->datetime = date('Y-m-d H:i:s');
        $message->text = $text;
        if($message->save()){
            return true;
        }
        else return false;
    }

    public static function createMessageButton(){
        return Html::a('Написать сообщение', ['messages/create'], ['class' => 'btn btn-primary']);
    }


    public static function getNewMessCount(){
        if(Yii::$app->user->isGuest)
            return false;

        $new_mess_count = Message::find()
            ->where(['receiver' => Yii::$app->user->id, 'reading' => 0])
            ->count();
        return $new_mess_count;
    }
    public function getShortText(){
        $text = strip_tags($this->text);
        $prefix = '';
        if(strlen($text) > 150)
            $prefix = '...';
        $text = substr($text, 0, 150) . $prefix;
        return $text;
    }
}
