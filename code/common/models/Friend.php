<?php

namespace common\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "friend".
 *
 * @property integer $id_from
 * @property integer $id_to
 *
 * @property User $idTo
 * @property User $idFrom
 */
class Friend extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'friend';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_from', 'id_to'], 'required'],
            [['id_from', 'id_to'], 'integer'],
            [['id_to'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_to' => 'id']],
            [['id_from'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_from' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_from' => 'Id From',
            'id_to' => 'Id To',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTo()
    {
        return $this->hasOne(User::className(), ['id' => 'id_to']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdFrom()
    {
        return $this->hasOne(User::className(), ['id' => 'id_from']);
    }

    public function deleteFriend(){
        $other_friend = Friend::findOne(['id_from' => $this->id_to, 'id_to' => $this->id_from]);
        if(isset($other_friend)){
            if($other_friend->delete() && $this->delete())
                return true;
            else return false;
        }
        return false;
    }
}
