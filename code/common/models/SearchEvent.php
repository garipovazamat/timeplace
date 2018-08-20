<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Event;

/**
 * SearchEvent represents the model behind the search form about `common\models\Event`.
 */
class SearchEvent extends Event
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'city_id', 'moderated'], 'integer'],
            [['name', 'fulldesc', 'shortdesc', 'datetime', 'place'], 'safe'],
            ['city_id', 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Event::find();


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'datetime' => $this->datetime,
            'city_id' => $this->city_id,
            'moderated' => $this->moderated,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'fulldesc', $this->fulldesc])
            ->andFilterWhere(['like', 'shortdesc', $this->shortdesc])
            ->andFilterWhere(['like', 'place', $this->place]);

        $session = Yii::$app->session;
        if($session->has('id_city'))
            $query->andFilterWhere([
                'city_id' => $session->get('id_city'),
            ]);

        $query->orderBy('id DESC');

        return $dataProvider;
    }
}
