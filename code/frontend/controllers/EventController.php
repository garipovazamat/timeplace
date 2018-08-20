<?php

namespace frontend\controllers;

use common\models\EventUser;
use common\models\Invite;
use common\models\Likes;
use common\models\Picture;
use common\models\PictureEvent;
use Yii;
use common\models\Event;
use frontend\models\SearchEvent;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\models\EventCategory;
use common\models\User;
use common\models\City;
use common\models\LoginForm;
use frontend\models\SignupForm;

/**
 * EventController implements the CRUD actions for Event model.
 */
class EventController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Event models.
     * @return mixed
     */
    public function actionIndex($category = 1, $page = 0)
    {
       $this->layout = false;
        $searchModel = new SearchEvent();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $category);
        $dataProvider->pagination->pageSize = 18;
        $dataProvider->pagination->page = $page;

        $loginForm = new LoginForm();
        $signupForm = new SignupForm();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'category_id' => $category,
            'signupForm' => $signupForm,
            'loginForm' => $loginForm,
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
        $this->enableCsrfValidation = false;
        if(Yii::$app->user->isGuest){
            $this->redirect(['site/login']);
        }
        $model = new Event();
        $event_category = [];
        $last_event_category = new EventCategory();
        for($i=0; $i<3; $i++)
            $event_category[] = new EventCategory();
        $pictures = $model->getPicturesForUpload(5);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->datetime = User::convertMyDatetime($model->datetime);
            $model->datetime_to = User::convertMyDatetime($model->datetime_to);
            $model->user_id = Yii::$app->user->id;
            if (Yii::$app->user->can('ModerateEvent'))
                $model->moderated = 1;
            $model->save();
            $last_event_category->attributes = EventCategory::findLastPost();
            $last_event_category->id_event = $model->id;
            $last_event_category->save();
            for($i=0; $i<count($pictures); $i++) {
                $pictures[$i]->imageFile = UploadedFile::getInstance($pictures[$i], "[$i]imageFile");
                if(isset($pictures[$i]) && isset($pictures[$i]->imageFile)) {
                    $pictures[$i]->upload();
                    $picture_event = new PictureEvent();
                    $picture_event->id_picture = $pictures[$i]->id;
                    $picture_event->id_event = $model->id;
                    $picture_event->save();
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'pictures' => $pictures,
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
        $model->fulldesc = strip_tags($model->fulldesc);
        $user_id = $model->user_id;
        $model->datetime = User::getMyDatetime($model->datetime);
        if(isset($model->datetime_to))
            $model->datetime_to = User::getMyDatetime($model->datetime_to);
        $event_category = EventCategory::findOne(['id_event' => $id]);
        $event_categories = [];
        for($i=0; $i<3; $i++)
            $event_categories[] = new EventCategory();
        $pictures = $model->getPicturesForUpload(5);
        if(empty($event_category))
            $event_category = new EventCategory();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            print_r(Yii::$app->request->post());
            $model->datetime = User::convertMyDatetime($model->datetime);
            $model->datetime_to = User::convertMyDatetime($model->datetime_to);
            $model->user_id = $user_id;
            $model->save();

            $event_category->attributes = EventCategory::findLastPost();
            $event_category->id_event = $model->id;
            $event_category->save();

            for($i=0; $i<count($pictures); $i++) {
                $pictures[$i]->imageFile = UploadedFile::getInstance($pictures[$i], "[$i]imageFile");
                if(isset($pictures[$i])&& isset($pictures[$i]->imageFile)) {
                    $pictures[$i]->upload();
                    $picture_event = new PictureEvent();
                    $picture_event->id_picture = $pictures[$i]->id;
                    $picture_event->id_event = $model->id;
                    $picture_event->save();
                }
            }
            $model->sendUpdateMessage();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'pictures' => $pictures,
                'event_category' => $event_categories
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

    public function actionRun($id){
        $user_id = Yii::$app->user->id;
        $event_user = new EventUser();
        $event_user->id_user = $user_id;
        $event_user->id_event = $id;
        $event_user->save();
        $invite = Invite::findOne(['id_user' => $user_id, 'id_event' => $id]);
        if(isset($invite))
            $invite->confirmInvite();
        $this->redirect(['view', 'id' => $id]);
    }

    public function actionRunout($id){
        $user_id = Yii::$app->user->id;
        $event_user = EventUser::findOne(['id_user' => $user_id, 'id_event' => $id]);
        if(isset($event_user))
            $event_user->delete();

        $this->redirect(['view', 'id' => $id]);
    }

    public function actionLike($id){
        $user_id = Yii::$app->user->id;
        $like = new Likes();
        $like->id_user = $user_id;
        $like->id_event = $id;
        $like->save();
        $this->redirect(['view', 'id' => $id]);
    }

    public function actionLikeout($id){
        $user_id = Yii::$app->user->id;
        $like = Likes::findOne(['id_user' => $user_id, 'id_event' => $id]);
        if(isset($like))
            $like->delete();
        $this->redirect(['view', 'id' => $id]);
    }

    public function actionMap(){
        return $this->render('map');
    }
}