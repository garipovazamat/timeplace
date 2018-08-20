<?php
namespace frontend\models;

use common\models\Picture;
use common\models\Timezone;
use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $sname;
    public $idCity;
    public $dateBorn;
    public $sex;
    public $email;
    public $password;
    public $aboutme;
    public $tz_val;
    public $idVk;
    public $idFacebook;

    public $big_image_src;
    public $miniature_src;
    public $bigminiature;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required', 'message' => 'Заполните пустое поле'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            //['sname', 'required', 'message' => 'Заполните пустое поле'],
            ['sname', 'string', 'min' => 2, 'max' => 255],

            ['idCity', 'required', 'message' => 'Заполните пустое поле'],

            ['dateBorn', 'date'],
            ['dateBorn', 'default'],

            ['sex', 'boolean'],
            ['sex', 'default'],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required', 'message' => 'Заполните пустое поле'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            //['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Этот адрес почты уже используется, если это ваша почта, то используйте его для входа либо восстановите пароль'],
            ['email', 'emailValidate'],
            ['password', 'required', 'message' => 'Заполните пустое поле'],
            ['password', 'string', 'min' => 6],
            ['aboutme' , 'default'],
            ['tz_val', 'required'],
            [['big_image_src', 'miniature_src', 'bigminiature'], 'default'],
            [['idVk', 'idFacebook'], 'safe']
        ];
    }

    public  function attributeLabels(){
        return [
            'username' => 'Имя',
            'sname' => 'Фамилия',
            'email' => 'E-mail',
            'aboutme' => 'О себе',
            'pagevk' => 'Страница вконтакте',
            'idCity' => 'Город',
            'id_picture' => 'Фотография',
            'last_visit' => 'Время последнего визита',
            'dateBorn' => 'Дата рождения',
            'sex' => 'Пол',
            'password' => 'Пароль',
            'tz_val' => 'Часовой пояс'
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->sname = $this->sname;
            $user->id_city = $this->idCity;
            $user->date_born = $this->dateBorn;
            $user->sex = $this->sex;
            $user->email = $this->email;
            $user->aboutme = $this->aboutme;
            $tz = Timezone::findOne(['utc' => $this->tz_val]);
            $user->id_tz = $tz->id;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if(isset($this->big_image_src)){
                $picture = new Picture();
                if($picture->downloadFromOut($this->big_image_src, $this->miniature_src, $this->bigminiature))
                    $user->id_picture = $picture->id;
            }
            if(isset($this->idVk))
                $user->id_vk = $this->idVk;

            if(isset($this->idFacebook))
                $user->id_facebook = $this->idFacebook;

            if ($user->save()) {
                $user_role = Yii::$app->authManager->getRole('user');
                Yii::$app->authManager->assign($user_role, $user->id);
                return $user;
            }
        }

        return null;
    }

    public function emailValidate(){
        $email_count = User::find()->where(['email' => $this->email])->andWhere('status != 0')->count();
        if($email_count>0){
            $this->addError('email', 'Этот адрес почты уже используется, если это ваша почта, то используйте его для входа либо восстановите пароль');
        }
    }
}
