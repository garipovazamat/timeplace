<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 06.11.2015
 * Time: 11:03
 */
namespace frontend\controllers;

use common\models\Category;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\helpers\Json;


/**
 * Category controller
 */

class CategoryController extends Controller
{
    /*public function behaviors()
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
                    'logout' => ['post'],
                ],
            ],
        ];
    }

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
*/
    public function actionIndex($id){
        return $this->render('index', ['id' => $id]);
    }

    public function actionOnecategory($id){
    }

    public function actionGetsubcat(){
        $this->enableCsrfValidation = false;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $cat_id = $parents[0];
                $out = Category::depCategoryList($cat_id, true);
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionGetsubcat2($id){
        $this->enableCsrfValidation = false;
        $subcats = Category::depCategoryList($id);
        foreach ($subcats as $key => $oneCatName) {
            echo "<option value='".$key."'>".$oneCatName."</option>";
        }

    }
}