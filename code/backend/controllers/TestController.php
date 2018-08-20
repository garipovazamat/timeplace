<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 11.11.2015
 * Time: 21:35
 */
namespace backend\controllers;

use common\models\Event;
use common\models\Picture;
use Yii;
use yii\web\Controller;


class TestController extends Controller{
    public function actionIndex(){
        return $this->renderAjax('index');
    }
}