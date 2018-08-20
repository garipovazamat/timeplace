<?php

namespace frontend\controllers;

use common\models\Message;
use common\models\User;
use Yii;
use yii\helpers\Url;

class MessagesController extends \yii\web\Controller
{
    public function actionShow(){
        return $this->render('show');
    }

    public function actionWrite($id){
        Url::remember();
        $myid = Yii::$app->user->id;
        $user = User::findById($id);
        $message = new Message();
        if($message->load(Yii::$app->request->post())){
            $message->sender = $myid;
            $message->receiver = $id;
            $message->datetime = date('Y-m-d H:i:s');
            $message->save();
            //$this->redirect(['messages/write', 'id' => $id]);
        }
        $all_messages = Message::find()->where(['sender' => $myid, 'receiver' => $id])
            ->orWhere(['sender' => $id, 'receiver' => $myid])->orderBy('datetime')->all();
        return $this->render('write', [
            'user' => $user,
            'message' => $message,
            'all_messages' => $all_messages
        ]);
    }

    public function actionCreate($id = null){
        $message = new Message();
        if(isset($id)){
            $message->receiver = $id;
        }
        if($message->load(Yii::$app->request->post())){
            $my_id = Yii::$app->user->id;
            if(Message::sendMessage($my_id, $message->receiver, $message->text))
                return $this->redirect(['messages/write', 'id' => $message->receiver]);
        } else {
            return $this->render('create', ['message' => $message]);
        }
    }

    public function actionDelete($id){
        $message = Message::findOne(['id' => $id]);
        if(isset($message))
            $message->delete();
        $this->redirect(Url::previous());
    }

}
