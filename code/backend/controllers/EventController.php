<?php

namespace backend\controllers;

use common\models\EventCategory;
use Yii;
use common\models\Event;
use common\models\SearchEvent;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\models\City;
use yii\helpers\Json;
use common\models\User;

/**
 * EventController implements the CRUD actions for Event model.
 */
class EventController extends Controller
{

    public function getCityFromRegion($region_id){
        $city = City::find()->where(['id_region' => $region_id])->asArray()->all();
        $city = ArrayHelper::map($city, 'id_city', 'name');
        //print_r($city);
        $city_list = [];
        $i=0;
        foreach($city as $key => $one_city){
            $city_list[$i]['id'] = $key;
            $city_list[$i]['name'] = $one_city;
            $i++;
        }
        return $city_list;
    }
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        //'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['moderator'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className()
            ],
        ];
    }

    /**
     * Lists all Event models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchEvent();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Event model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Event model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Event();
        $event_category = new EventCategory();

        if ($model->load(Yii::$app->request->post())) {
            $model->datetime = User::convertMyDatetime($model->datetime);
            if($model->save()) {
                $event_category->load(Yii::$app->request->post());
                $event_category->id_event = $model->id;
                $event_category->save();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'event_category' => $event_category
            ]);
        }
    }

    /**
     * Updates an existing Event model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $event_category = EventCategory::findOne(['id_event' => $id]);

        if(empty($event_category))
            $event_category = new EventCategory();

        if ($model->load(Yii::$app->request->post())) {
            $model->datetime = User::convertMyDatetime($model->datetime);
            if($model->save()) {
                $event_category->load(Yii::$app->request->post());
                $event_category->id_event = $id;
                $event_category->save();
                //return $this->redirect(['view', 'id' => $model->id]);
            }
            echo $model->datetime;
        } else {
            return $this->render('update', [
                'model' => $model,
                'event_category' => $event_category
            ]);
        }
    }

    /**
     * Deletes an existing Event model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Event model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Event the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Event::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSubregion() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $region_id = $parents[0];
                $out = self::getCityFromRegion($region_id);
                // the getSubCatList function will query the database based on the
                // cat_id and return an array like below:
                // [
                //    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
                //    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
                // ]
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=> '', 'selected'=>'']);
        print_r(self::getCityFromRegion(2));
    }

    /**
     * Модерирует на противоположный, т.е. если до этого прошла модерацию, то установит как не прошла
     */
    public function actionModerateevent($id){
        $event = Event::findOne(['id' => $id]);
        if(!empty($event)){
            if($event->moderated == 1)
                $event->moderated = 2;
            if($event->moderated == 2)
                $event->moderated = 1;
            $event->save(false);
        }
        return $this->redirect(['event/index']);
    }

    public function actionModerate($id, $ismoderated = 1){
        $event = Event::findOne(['id' => $id]);
        if($ismoderated){
            $event->moderated = Event::MODERATED_YES;
        } else $event->moderated = Event::MODERATED_NO;
        $event->update(false);
        return $this->redirect(['event/index']);
    }
}
