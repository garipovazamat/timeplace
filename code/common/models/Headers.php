<?php

namespace common\models;

use Yii;
use yii\bootstrap\Html;

/**
 * This is the model class for table "headers".
 *
 * @property integer $id
 * @property integer $id_city
 * @property integer $id_picture
 * @property Picture $picture
 *
 * @property Picture $idPicture
 */
class Headers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'headers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_city', 'id_picture'], 'integer'],
            [['id_picture'], 'exist', 'skipOnError' => true, 'targetClass' => Picture::className(), 'targetAttribute' => ['id_picture' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_city' => 'Id City',
            'id_picture' => 'Id Picture',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPicture(){
        return $this->hasOne(Picture::className(), ['id' => 'id_picture']);
    }

    public function getCity(){
        return $this->hasOne(City::className(), ['id_city' => 'id_city']);
    }

    public function getDeleteButton(){
        return Html::a('Удалить', ['headers/delete', 'id' => $this->id],
            [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы действительно хотите удалить шапку?',
                    'method' => 'post',
                ],
            ]);
    }

    public function afterDelete(){
        $this->picture->deletePicture();
        return true;
    }

    public static function getThisHeaders(){
        $session = Yii::$app->session;
        if($session->has('id_city')) {
            $mycity_id = $session->get('id_city');
            $head_count = Headers::find()->where(['id_city' => $mycity_id])->count();
            if($head_count == 0)
                $mycity_id = 2419;
        }
        else $mycity_id = 2419;

        $headers = Headers::find()->where(['id_city' => $mycity_id])->all();
        return $headers;
    }
}
