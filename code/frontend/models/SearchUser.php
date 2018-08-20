<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 23.11.2015
 * Time: 15:44
 */
namespace frontend\models;

use common\models\User;
use yii\data\ActiveDataProvider;


class SearchUser extends User{

    public $free_field;
    public $age_from;
    public $age_to;

    public function rules()
    {
        return [
            [['age_from', 'age_to', 'free_field', 'sname', 'username', 'id_city', 'sex'], 'default'],
        ];
    }
    public function search($params = null){
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'sname', $this->sname])
            ->andFilterWhere(['id_city' => $this->id_city])
            ->andFilterWhere(['sex' => $this->sex])
            ->andFilterWhere(['status' => User::STATUS_ACTIVE]);

        $query->andFilterWhere(
            ['or',
                ['like', 'username', $this->free_field],
                ['like', 'sname', $this->free_field]
            ]);


        return $dataProvider;
    }

}