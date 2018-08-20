<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "picture_event".
 *
 * @property integer $id
 * @property integer $id_picture
 * @property integer $id_event
 * @property string $date_add
 *
 * @property Picture $idPicture
 * @property Event $idEvent
 */
class PictureEvent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'picture_event';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_picture', 'id_event'], 'integer'],
            [['date_add'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_picture' => 'Id Picture',
            'id_event' => 'Id Event',
            'date_add' => 'Date Add',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPicture()
    {
        return $this->hasOne(Picture::className(), ['id' => 'id_picture']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdEvent()
    {
        return $this->hasOne(Event::className(), ['id' => 'id_event']);
    }
}
