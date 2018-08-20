<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 26.12.2015
 * Time: 21:31
 */
namespace common\models;

use frontend\models\SignupForm;

class Facebook{
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

    public function initFacebook(){
        $fb = new \Facebook\Facebook([
            'app_id' => '102474660129796',
            'app_secret' => '31df04a597d8ae6893c6abfb01a9eb0c',
            'default_graph_version' => 'v2.2',
        ]);
        $helper = $fb->getRedirectLoginHelper();
        try {
            $accessToken = $helper->getAccessToken();
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        if(isset($accessToken)){
            $fb->setDefaultAccessToken($accessToken);
            try {
                $response = $fb->get('/me?fields=name,email,first_name,last_name,birthday,location', $accessToken);
                $userNode = $response->getGraphUser();
                $userPage = $response->getGraphPage();
            } catch(\Facebook\Exceptions\FacebookResponseException $e) {
                // When Graph returns an error
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch(\Facebook\Exceptions\FacebookSDKException $e) {
                // When validation fails or other local issues
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }

            $this->user_id = $userNode->getId();
            $this->name = $userNode->getFirstName();
            $this->sname = $userNode->getLastName();
            $this->email = $userNode->getEmail();
            $dateBorn = $userNode->getBirthday()->getTimestamp();
            $this->dateBorn = date('Y-m-d', $dateBorn);
        }
    }

    public function getSignupForm(){
        $signupForm = new SignupForm();
        $signupForm->username = $this->name;
        $signupForm->sname = $this->sname;
        $signupForm->email = $this->email;
        $signupForm->idFacebook = $this->user_id;
        return $signupForm;
    }

    public function isHaveUser(){
        $user = User::findOne(['id_facebook' => $this->user_id, 'status' => User::STATUS_ACTIVE]);
        if(!isset($user))
            $user = User::findOne(['email' => $this->email, 'status' => User::STATUS_ACTIVE]);
        if(isset($user))
            return $user;
        else return false;
    }






}