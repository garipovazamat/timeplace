<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "city".
 *
 * @property integer $id_city
 * @property integer $id_region
 * @property integer $id_country
 * @property string $name
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_region', 'id_country'], 'required'],
            [['id_region', 'id_country'], 'integer'],
            [['name'], 'string', 'max' => 255],
            ['id_city', 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_city' => 'Город',
            'id_region' => 'Id Region',
            'id_country' => 'Id Country',
            'name' => 'Город',
        ];
    }

    public function getCitypriority(){
        return $this->hasOne(CityPriority::className(), ['id_city' => 'id_city']);
    }

    public static function cityList($id_country = 1){
        $cities = City::find()->where(['id_country' => $id_country])
            ->joinWith('citypriority')
            ->orderBy([new \yii\db\Expression('-1 * priority DESC')])
            ->all();
        return ArrayHelper::map($cities, 'id_city', 'name');
    }

    public static function allCityList(){
        $cities = City::find()
            ->joinWith('citypriority')
            ->orderBy([new \yii\db\Expression('-1 * priority DESC')])
            ->all();
        return ArrayHelper::map($cities, 'id_city', 'name');
    }

    /**
     * @return City|bool
     * Возвращает город в сессии
     */
    public static function getSessionCity(){
        $session = Yii::$app->session;
        if($session->has('id_city')){
            $id_city = $session->get('id_city');
            return City::findOne(['id_city' => $id_city]);
        } else return new City();
    }
}
