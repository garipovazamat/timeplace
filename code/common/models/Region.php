<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "region".
 *
 * @property integer $id_region
 * @property integer $id_country
 * @property string $name
 */
class Region extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'region';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_country'], 'required'],
            [['id_country'], 'integer'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_region' => 'Id Region',
            'id_country' => 'Id Country',
            'name' => 'Name',
        ];
    }

    public static function regionsList(){
        $regions = Region::find()->where(['id_country' => 1])->all();
        return ArrayHelper::map($regions, 'id_region', 'name');
    }
}
