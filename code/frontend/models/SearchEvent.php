<?php

namespace frontend\models;

use common\models\Category;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Event;
use yii\db\ActiveQuery;

/**
 * SearchEvent represents the model behind the search form about `common\models\Event`.
 */
class SearchEvent extends Event
{
    public $desc_search;
    public $dateFrom;
    public $dateTo;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'city_id', 'moderated'], 'integer'],
            [['name', 'fulldesc', 'shortdesc', 'datetime', 'place'], 'safe'],
            ['desc_search', 'default'],
            [['dateFrom', 'dateTo'], 'safe']
        ];
    }

    public function validateDate($attribute, $params)
    {
        if (strtotime($this->dateFrom) > strtotime($this->dateFrom))
            $this->addError($attribute, 'Дата окончания раньше начала');
    }

    public function attributeLabels()
    {
        return [
            'dateFrom' => 'Начало интервала',
            'dateTo' => 'Окончание интервала'
        ];

    }

    /**
     * @inheritdoc
     */
    /*public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }*/

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public $category;

    public function search($params, $category_id, $main = false)
    {
        if(isset($category_id) && $category_id != 1){
            $category = Category::findOne(['id' => $category_id]);
            $query = $category->findAllEventsQuery();
        }
        else
            $query = Event::find();

        $query->orderBy('datetime');

        /*if(isset($category_id)){
            $query->joinWith('eventCategories')->andWhere(['id_category' => $category_id]);
        }
        $query->orderBy('datetime DESC');*/

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

        if(isset($this->desc_search)){
            $query->andFilterWhere(['like', 'fulldesc', $this->desc_search]);
        }

        $session = Yii::$app->session;
        if($session->has('id_city'))
            $query->andWhere([
                'city_id' => $session->get('id_city'),
            ]);

        if($main == false)
            $query->andWhere("datetime >= NOW() OR datetime_to >= NOW()");
        else
            $query->andWhere("datetime >= NOW()");

        $query->andWhere(['opened' => 1]);
        $query->andWhere('moderated = 1 OR moderated = 0');

        if(!empty($this->dateFrom) && !empty($this->dateTo)){
            $query->andWhere("('$this->dateFrom' >= datetime  AND '$this->dateFrom' < datetime_to) " .
                "OR ('$this->dateTo' >= datetime AND '$this->dateTo' < datetime_to)");
        }


        return $dataProvider;
    }
}
