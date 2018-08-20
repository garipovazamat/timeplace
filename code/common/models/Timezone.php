<?php

namespace common\models;

use Yii;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "timezone".
 *
 * @property integer $id
 * @property integer $utc
 * @property string $name
 */
class Timezone extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'timezone';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['utc'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'utc' => 'Utc',
            'name' => 'Name',
        ];
    }

    public static function timezoneList(){
        $tz = Timezone::find()->all();
        $array = [];
        foreach($tz as $one_tz)
            $array[$one_tz->id] = $one_tz->name . ' (UTC+' . $one_tz->utc . ')';
        return $array;
    }
}
