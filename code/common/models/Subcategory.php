<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "subcategory".
 *
 * @property integer $id_main
 * @property integer $id_sub
 *
 * @property Category $idMain
 * @property Category $idSub
 */
class Subcategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subcategory';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_main', 'id_sub'], 'required'],
            [['id_main', 'id_sub'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_main' => 'Категория',
            'id_sub' => 'Подкатегория',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMain()
    {
        return $this->hasOne(Category::className(), ['id' => 'id_main']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSub()
    {
        return $this->hasOne(Category::className(), ['id' => 'id_sub']);
    }

    public static function primaryKey(){
        return ['id_main', 'id_sub'];
    }
}
