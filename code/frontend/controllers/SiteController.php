<?php
namespace frontend\controllers;

use common\models\Facebook;
use common\models\City;
use frontend\models\SearchEvent;
use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use linslin\yii2\curl;
use common\models\Vk;
use yii\helpers\Url;

/**
 * Site controller
 */
class SiteController extends Controller
{

    public $enableCsrfValidation = false;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
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
                    //'logout' => ['post'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $this->layout = false;
        $searchModel = new SearchEvent();
        $dataProvider = $searchModel->search(null, 1, true);
        $dataProvider->pagination->setPageSize(40);
        $lentaEvents = $dataProvider->getModels();

        $loginForm = new LoginForm();

        $signupForm = new SignupForm();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'lentaEvents' => $lentaEvents,
            'signupForm' => $signupForm,
            'loginForm' => $loginForm,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        $this->layout = 'oldmain';
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

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $this->layout = 'oldmain';
        $model = new SignupForm();
        $model->attributes = $_GET['form'];
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionLoginvk($code){
        $vk = new Vk();
        $vk->getUser($code);
        $user = $vk->isHaveUser();
        if($user) {
            Yii::$app->user->login($user);
            $this->redirect(['user/index']);
        }
        else
            $this->redirect(['site/signup', 'form' => $vk->getSignupForm()]);
    }

    public function actionLoginfacebook($code){
        Yii::$app->session->open();
        $facebook = new Facebook();
        $facebook->initFacebook();
        $user = $facebook->isHaveUser();
        if($user){
            Yii::$app->user->login($user);
            $this->redirect(['user/index']);
        }
        else
            $this->redirect(['site/signup', 'form' => $facebook->getSignupForm()]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionSetcity(){
        $city = new City();
        $city->load(Yii::$app->request->post());
        $session = Yii::$app->session;
        if(!empty($city->id_city))
            $session->set('id_city', $city->id_city);
        //$this->redirect(Url::previous());
        $this->goHome();
    }

    public function actionConfid(){
        return $this->render('confid');
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
