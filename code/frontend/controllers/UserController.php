<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 09.11.2015
 * Time: 12:27
 */
namespace frontend\controllers;

use common\models\Event;
use common\models\EventUser;
use common\models\Friend;
use common\models\Invite;
use common\models\Picture;
use common\models\Request;
use common\models\SearchEvent;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use frontend\models\SearchUser;
use common\models\Message;

class UserController extends Controller{

    const SESSION_URL_NAME = 'user_index';

    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions(){
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex($id = null){
        /*if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }*/
        Url::remember('', 'user_index');
        $searchModel = new SearchEvent();
        if(empty($id))
            $model = Yii::$app->user->identity;
        else
            $model = User::findOne(['id' => $id]);

        return $this->render('index', ['model' => $model]);
    }

    public function actionEdit(){
        $model = $this->findModel(Yii::$app->user->id);
        $photo = Picture::findOne(['id' => $model->id_picture]);
        if(empty($photo)){
            $photo = new Picture();
        }
        if ($model->load(Yii::$app->request->post())) {
            $photo->imageFile = UploadedFile::getInstance($photo, 'imageFile');
            if(isset($photo->imageFile)) {
                $photo->upload();
                $model->id_picture = $photo->id;
            }
            $model->save();
            return $this->redirect(['index']);
        }
        else{
            return $this->render('edit', [
                'model' => $model,
                'photo' => $photo
                ]);
        }
    }

    /**
     * @param $id
     * @return User
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSearch($page = 0){
        $searchModel = new SearchUser();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 20;
        $dataProvider->pagination->page = $page;
        return $this->render('search', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionFriends(/*$id = false*/){
        /* @var $user User*/
        $user = Yii::$app->user->identity;
        $friends = $user->friends;
        $request_friends = $user->request;
        return $this->render('friends', [
            'friends' => $friends,
            'requests' => $request_friends
        ]);
        /*if($id)
            return $this->render('friends', ['id' => $id]);
        else
            return $this->render('friends');*/
    }

    public function actionSendrequest($id){
        $user = $this->findModel($id);
        $user->sendRequest();
        $this->redirect(['user/index', 'id' => $id]);
    }

    public function actionConfirmrequest($id_request){
        $request = Request::findOne(['id' => $id_request]);
        $request->confirmRequest();
        return $this->redirect(['user/friends']);
    }

    public function actionAddfriend($id){
        $friend = User::findById($id);
        $request = $friend->isHaveRequestToMe();
        if($request)
            $request->confirmRequest();
        else
            $friend->sendRequest();
        return $this->redirect(Url::previous(self::SESSION_URL_NAME));
    }


    public function actionRefuserequest($id_request){
        $request = Request::findOne(['id' => $id_request]);
        $request->delete();
        return $this->redirect(['user/friends']);
    }
    public function actionDeletefriend($id_friend){
        $friend = Friend::findOne(['id_from' => $id_friend]);
        $friend->deleteFriend();
        return $this->redirect(['user/friends']);
    }

    public function actionDelete(){
        if(!Yii::$app->user->isGuest){
            $user = Yii::$app->user->identity;
            $user->deleteUser();
        }
        return $this->redirect(['site/index']);
    }

    public function actionInvites(){
        $user = Yii::$app->user->identity;
        $myInvites = $user->getMyInvites();
        return $this->render('invites', ['myInvites' => $myInvites]);
    }

    public function actionInviteuser($id_user, $id_event){
        $user = Yii::$app->user->identity;
        $user->sendInvite($id_user, $id_event);
        $this->redirect(['user/friends', 'id' => $id_event]);
    }

    public function actionInviteconfirm($id){
        $invite = Invite::findOne(['id' => $id]);
        $isRun = EventUser::find()
            ->where(['id_user' => $invite->id_user, 'id_event' => $invite->id_event])
            ->count();
        if(!$isRun){
            EventUser::participate($invite);
            $invite->confirmInvite();
        }
        $this->redirect(['user/invites']);
    }

    public function actionInvitedelete($id){
        $invite = Invite::findOne(['id' => $id]);
        if(isset($invite))
            $invite->delete();
        $this->redirect(['user/invites']);
    }

    public function actionMyevents(){
        $iam = Yii::$app->user->identity;
        $events = Event::find()->where(['user_id' => $iam->id])->all();
        return $this->render('myevents', ['events' => $events]);
    }

}