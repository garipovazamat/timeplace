<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 06.12.2015
 * Time: 14:02
 */
namespace common\models;

use frontend\models\SignupForm;
use linslin\yii2\curl\Curl;
use yii\helpers\Url;

Class Vk {
    public $user_id;
    public $email;
    public $big_image_src;
    public $miniature_src;
    public $bigminiature;
    public $name;
    public $sname;
    public $sex;
    public $dateBorn;
    public $city;

    private $access_token;


    /**
     * @param $code
     * @return mixed
     * Получает все поля объекта, и возвращает основные данные пользователя ВК (имя, фамилия и т.д.)
     */
    public function getUser($code){
        $curl = new Curl();
        $response = $curl->get('https://oauth.vk.com/access_token?'.
            'client_id=5175774&'.
            'client_secret=F5WEWblbNBqzXNLmTKqL&'.
            'redirect_uri='.Url::toRoute(['site/loginvk'], true).
            '&code='.$code);
        $response = json_decode($response);

        $this->user_id = $response->user_id;
        $this->email = $response->email;
        $this->access_token = $response->access_token;

        $user = $curl->get('https://api.vk.com/method/users.get?user_ids='.$this->user_id.
            '&access_token='.$this->access_token.
            '&fields=sex,bdate,city,has_photo,photo_50,photo_100,photo_max_orig,domain,photo_id');
        $user = json_decode($user);

        $this->miniature_src = $user->response[0]->photo_50;
        $this->bigminiature = $user->response[0]->photo_100;
        $photo = $curl->get('https://api.vk.com/method/photos.getById?photos='.$user->response[0]->photo_id);
        $photo = json_decode($photo);
        $this->big_image_src = $photo->response[0]->src_big;
        $this->name = $user->response[0]->first_name;
        $this->sname = $user->response[0]->last_name;
        if($user->response[0]->sex == 2)
            $this->sex = 0;
        if($user->response[0]->sex == 1)
            $this->sex = 1;
        $this->dateBorn = str_replace('.', '-', $user->response[0]->bdate);
        $this->dateBorn = strtotime($this->dateBorn);
        $this->dateBorn = date('Y-m-d', $this->dateBorn);

        $city = $curl->get('https://api.vk.com/method/database.getCitiesById?'.
        'city_ids='.$user->response[0]->city);
        $city = json_decode($city);
        $city = $city->response[0]->name;
        $this->city = $city;
        return $user->response[0];
    }

    /**
     * @return bool|null|static
     * Если пользователь с таким email существует возвращает объект User, иначе false
     */
    public function isHaveUser(){
        $user = User::findOne(['id_vk' => $this->user_id, 'status' => User::STATUS_ACTIVE]);
        if(!isset($user))
            $user = User::findOne(['email' => $this->email, 'status' => User::STATUS_ACTIVE]);
        if(isset($user))
            return $user;
        else return false;
    }

    public function getSignupForm(){
        $signupForm = new SignupForm();
        $signupForm->username = $this->name;
        $signupForm->sname = $this->sname;
        $signupForm->email = $this->email;
        $signupForm->sex = $this->sex;
        $city = City::findOne(['name' => $this->city]);
        if(isset($city))
            $signupForm->idCity = $city->id_city;
        $signupForm->big_image_src = $this->big_image_src;
        $signupForm->miniature_src = $this->miniature_src;
        $signupForm->bigminiature = $this->bigminiature;
        $signupForm->idVk = $this->user_id;
        return $signupForm;
    }
}