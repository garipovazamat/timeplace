<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "event_category".
 *
 * @property integer $id_event
 * @property integer $id_category
 *
 * @property Event $idEvent
 * @property Category $idCategory
 */
class EventCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_event', 'id_category'], 'required'],
            [['id_event', 'id_category'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_event' => 'Id Event',
            'id_category' => 'Категория',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdEvent()
    {
        return $this->hasOne(Event::className(), ['id' => 'id_event']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'id_category']);
    }
    public static function primaryKey(){
        return ['id_event', 'id_category'];
    }

    /**
     * Находит последнюю event_category, пришедшую из формы
     */
    public static function findLastPost(){
        $last_number = 0;
        if(isset($_POST['EventCategory'])){
            while(!empty($_POST['EventCategory'][$last_number]['id_category'])){
                $last_number++;
            }
            $last_number--;
            return $_POST['EventCategory'][$last_number];
        }
        else
            return false;
    }
}
