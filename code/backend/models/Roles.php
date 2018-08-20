<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 09.11.2015
 * Time: 12:52
 */
namespace app\models;

use yii\helpers\ArrayHelper;

class Roles extends \yii\db\ActiveRecord{

    public static function tableName()
    {
        return 'auth_assignment';
    }

    public function rules()
    {
        return [
            [['item_name', 'user_id'], 'required'],
            ['created_at', 'default']
        ];
    }

}
?>