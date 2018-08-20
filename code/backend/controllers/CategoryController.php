<?php

namespace backend\controllers;

use common\models\Picture;
use common\models\Subcategory;
use Yii;
use common\models\Category;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Connection;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
{
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
            /*'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],*/
        ];
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Category::find(),
        ]);
        $dataProvider->pagination->setPageSize(100);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
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
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();
        $picture[0] = new Picture();
        $picture[1] = new Picture();
        $subcat = Category::createSubcategory();

        if ($model->load(Yii::$app->request->post())) {
            //$picture->load();
            $picture[0]->imageFile = UploadedFile::getInstance($picture[0], '[0]imageFile');
            $picture[1]->imageFile = UploadedFile::getInstance($picture[1], '[1]imageFile');
            if(isset($picture[0]->imageFile)) {
                $picture[0]->uploadForCategory();
                $model->id_picture = $picture[0]->id;
            }
            if(isset($picture[1]->imageFile)) {
                $picture[1]->uploadForCategory(true);
                $model->id_micropicture = $picture[1]->id;
            }
            $model->save();
            for($i=0; $i<count($subcat); $i++) {
                if (isset($_POST['Subcategory'][$i])) {
                    $subcat[$i]->attributes = $_POST['Subcategory'][$i];
                    $subcat[$i]->id_sub = $model->id;
                    $subcat[$i]->save();
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }
        else {
            return $this->render('create', [
                'model' => $model,
                'subcat' => $subcat,
                'picture' => $picture,
            ]);
        }
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $subcats = Subcategory::find()->where(['id_sub' => $id])->all();
        $picture[0] = Picture::findOne(['id' => $model->id_picture]);
        $picture[1] = Picture::findOne(['id' => $model->id_micropicture]);
        if(empty($subcats))
            $subcats = Category::createSubcategory();
        if(empty($picture[0]))
            $picture[0] = new Picture();
        if(empty($picture[1]))
            $picture[1] = new Picture();

        if ($model->load(Yii::$app->request->post())) {
            $picture[0]->imageFile = UploadedFile::getInstance($picture[0], '[0]imageFile');
            $picture[1]->imageFile = UploadedFile::getInstance($picture[1], '[1]imageFile');
            if(!empty($picture[0]->imageFile)){
                $picture[0]->uploadForCategory();
                $model->id_picture = $picture[0]->id;
            }
            if(!empty($picture[1]->imageFile)){
                $picture[1]->uploadForCategory();
                $model->id_micropicture = $picture[1]->id;
            }
            $model->save();
            for($i=0; $i<count($subcats); $i++) {
                if (isset($_POST['Subcategory'][$i])) {
                    $subcats[$i]->attributes = $_POST['Subcategory'][$i];
                    $subcats[$i]->id_sub = $model->id;
                    $subcats[$i]->save();
                }
            }
            //print_r($_FILES);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'subcat' => $subcats,
                'picture' => $picture,
            ]);
        }
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this_category = $this->findModel($id);
        if($this_category->delete())
            echo 'good';
        else print_r($this_category->errors);
        return $this->redirect(['index']);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id){
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
