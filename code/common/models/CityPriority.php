<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "city_priority".
 *
 * @property integer $id_city
 * @property integer $priority
 */
class CityPriority extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'city_priority';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_city'], 'required'],
            [['id_city', 'priority'], 'integer'],
            ['id_city', 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_city' => 'Id City',
            'priority' => 'Priority',
        ];
    }
}
