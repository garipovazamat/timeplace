<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use common\models\City;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'selectcity'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
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

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $this->enableCsrfValidation = false;
        /*$role = Yii::$app->authManager->getRole('admin');
        $permit = Yii::$app->authManager->getRole('moderator');
        Yii::$app->authManager->addChild($role, $permit);
        $permit = Yii::$app->authManager->createPermission('editrole');
        $permit->description = 'Право редактировать роль пользователей';
        Yii::$app->authManager->add($permit);
        Yii::$app->authManager->addChild($role, $permit);*/
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSelectcity($id_country){
        $countCity = City::find()
            ->where(['id_country' => $id_country])
            ->count();
        $cities = City::cityList($id_country);
        if($countCity > 0){
            foreach($cities as $city_id => $city)
                echo "<option value='".$city_id."'>".$city."</option>";
        }
        else{
            echo "<option>-</option>";
        }
    }
}
