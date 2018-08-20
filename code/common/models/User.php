<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $sname
 * @property integer $last_visit
 * @property string $aboutme
 * @property string $pagevk
 * @property integer $id_vk
 * @property integer $id_facebook
 * @property integer $id_city
 * @property integer $id_tz
 * @property integer $id_picture
 * @property string $date_born
 * @property boolean $sex
 * @property Message[] message
 * @property City $city
 * @property User friends
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     *
     */
    const STATUS_DELETED = 0;
    /**
     *
     */
    const STATUS_ACTIVE = 10;

    /**
     * @var
     */
    public $allname;
    /**
     * @var
     */
    public $allname_email;

    /**
     *
     */
    public function afterFind(){
        $this->allname = $this->username . ' ' . $this->sname;
        $this->allname_email = $this->allname . ' <' . $this->email . '>';
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @return array
     */
    public  function attributeLabels(){
        return [
            'username' => 'Имя',
            'sname' => 'Фамилия',
            'email' => 'E-mail',
            'aboutme' => 'О себе',
            'pagevk' => 'Страница вконтакте',
            'id_city' => 'Город',
            'id_picture' => 'Фотография',
            'last_visit' => 'Время последнего визита',
            'date_born' => 'Дата рождения',
            'sex' => 'Пол',
            'id_tz' => 'Часовой пояс'
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['aboutme', 'pagevk'], 'default'],
            [['email', 'username'], 'required'],
            [['last_visit', 'sname'], 'default'],
            [['id_city', 'id_picture'], 'default'],
            ['date_born', 'default'],
            ['date_born', 'default'],
            ['id_tz', 'default', 'value' => 2],
            [['id_vk', 'id_facebook'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUseremail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @param $id
     * @return null|User
     */
    public static function findById($id){
        return static::findOne(['id' => $id]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity(){
        return $this->hasOne(City::className(), ['id_city' => 'id_city']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPicture(){
        return $this->hasOne(Picture::className(), ['id' => 'id_picture']);
    }

    /**
     * @return array
     * Получение списка всех пользователей в виде ['id' => 'Полное Имя <email>']
     */
    public static function usersList(){
        $users = User::find()->all();
        return ArrayHelper::map($users, 'id', 'allname_email');
    }

    /**
     * @return bool|\yii\rbac\Role
     * Получение роли пользователя
     */
    public function getUserRole(){
        if(!$this->isNewRecord) {
            $user_role = Yii::$app->authManager->getRolesByUser($this->id);
            return $user_role;
        }
        else return false;
    }

    public function isModerator(){
        $role = $this->getUserRole();
    }

    /**
     * @return bool|string
     * Получение возраста
     */
    public function getAge(){
        if(isset($this->date_born)) {
            $dateBorn = $this->date_born;
            $dateBorn_timestamp = strtotime($dateBorn);
            $age = date('Y') - date('Y', $dateBorn_timestamp);
            $dateBorn = explode('-', $dateBorn);
            if (date('md', $dateBorn_timestamp) > date('md')) {
                $age--;
            }
            return $age;
        }
        else return 'не указано';
    }

    /**
     * @return string
     * Функция устарела
     */
    public function showCityName(){
        $city = City::findOne(['id_city' => $this->id_city]);
        return $city->name;
    }
    /**
     * @return Picture|static
     * Получение фото
     */
    public function getPhoto(){
        if(isset($this->id_picture))
            return Picture::findOne(['id' => $this->id_picture]);
        else return new Picture();
    }
    /**
     * @param $datetime
     * @return mixed
     * Возвращает дата и время
     */
    public static function getMyDatetime($datetime){
        if(Yii::$app->user->isGuest)
            return $datetime;
        else{
            if(isset($datetime)) {
                $user = Yii::$app->user->identity;
                $tz = Timezone::findOne(['id' => $user->id_tz]);
                $current_tz = date('Z');
                $time = strtotime($datetime);
                $user_time = $time - $current_tz + $tz->utc * 3600;
                $datetime = date('Y-m-d H:i:s', $user_time);
                return $datetime;
            }
            else return false;
        }
    }
    public static function convertMyDatetime($datetime){
        if(empty($datetime))
            return null;
        if(Yii::$app->user->isGuest)
            return $datetime;
        else{
            $user = Yii::$app->user->identity;
            $tz = Timezone::findOne(['id' => $user->id_tz]);
            $server_tz = date('Z');
            $user_tz = $tz->utc*3600;
            $difference = $server_tz - $user_tz;
            $datetime = strtotime($datetime) + $difference;
            return date('Y-m-d H:i:s', $datetime);
        }
    }
    /**
     * @return Event[]
     * Получение мероприятий в которых участвует пользователь
     *если $end = true, то получение мероприятий, которые завершены
     * $end = false, то получение мероприятий, которые ещё не начались
     */
    public function getParticipateEvents($end = false){
        //$current_datetime = date('Y-m-d H:i:s');
        $events = [];
        $event_users = EventUser::find()->where(['id_user' => $this->id])->joinWith('event');
        if($end == false)
            $event_users->andWhere("(datetime_to IS NOT NULL AND (datetime_to >= NOW()))".
                " OR (datetime_to IS NULL AND (datetime >= NOW()))");
        else
            $event_users->andWhere("(datetime_to IS NOT NULL AND (datetime_to < NOW()))".
                " OR (datetime_to IS NULL AND (datetime < NOW()))");
        $event_users = $event_users->all();
        foreach($event_users as $one_event_user)
            $events[] = $one_event_user->event;
        return $events;
    }

    /**
     * @return \yii\db\ActiveQuery
     * Связь на получение присланных к User сообщений
     */
    public function getMessage(){
        return $this->hasMany(Message::className(), ['sender' => 'id'])
            ->orderBy('datetime');
    }
    /**
     * @param $text
     * @return mixed
     * Отправляет пользователю User сообщение от текущего пользователя
     */
    public function sendMessage($text){
        if(!Yii::$app->user->isGuest)
            return Message::sendMessage(Yii::$app->user->id, $this->id, $text);
        else
            false;
    }

    public function getFriends(){
        return $this->hasMany(static::className(), ['id' => 'id_to'])
            ->viaTable('friend', ['id_from' => 'id'])->from('user user2');
    }

    public function sendRequest(){
        if(!Yii::$app->user->isGuest) {
            if($this->isSendRequest())
                return false;
            $request = new Request();
            $request->id_from = Yii::$app->user->id;
            $request->id_to = $this->id;
            $request->add_datetime = date('Y-m-d H:i:s');
            return $request->save();
        }
        else return false;
    }

    public function isSendRequest(){
        if(!Yii::$app->user->isGuest){//Если не гость
            $myid = Yii::$app->user->id;
            // Проверить,может быть уже есть в друзьях
            $friend_count = Friend::find()->where(['id_from' => $myid, 'id_to' => $this->id])
                ->count();
            if($friend_count > 0)
                return true;

            // Проверить наличие такой заявки от меня или ко мне
            $request_count = Request::find()
                ->where(['id_from' => $myid, 'id_to' => $this->id])
                ->orWhere(['id_from' => $this->id, 'id_to' => $myid])
                ->count();
            if($request_count > 0)
                return true;
            else return false;
        }
        else return false;
    }

    public function requestMenuItem(){
        if(!Yii::$app->user->isGuest) {
            if(!$this->isSendRequest()) {
                return $item = [
                    'label' => 'Добавить в друзья',
                    'url' => ['user/sendrequest', 'id' => $this->id]
                ];
            }
            return [];
        }
        return [];
    }

    public function getRequest(){
        return $this->hasMany(Request::className(), ['id_to' => 'id']);
    }

    public static function myFriendsItem(){
        $myid = Yii::$app->user->id;
        $requests_count = Request::find()->where(['id_to' => $myid, 'confirm' => 0])->count();
        if($requests_count > 0)
            $label = 'Мои друзья (' . $requests_count . ')';
        else $label = 'Мои друзья';

        return [
            'label' => $label,
            'url' => ['user/friends']
        ];
    }

    public function getDeleteFriendButton(){
        return Html::a('Удалить из друзей',
            ['user/deletefriend', 'id_friend' => $this->id],
            ['class' => 'btn btn-primary']
        );
    }

    public function deleteUser(){
        $this->status = self::STATUS_DELETED;

        $this->save();
    }

    public function getSelfDeleteButton(){
        if(!Yii::$app->user->isGuest){
            return Html::a('Удалить свою страницу',
                ['user/delete'],
                [
                    'data' => [
                        'confirm' => 'Вы действительно хотите удалить мероприятие?',
                        'method' => 'post',
                    ]
                ]
            );
        }
        else return false;
    }

    public function setLastVisit(){
        $this->last_visit = time();
        $this->update();
    }


    public function isOnline(){
        $current_time = time();
        $user_time = $this->last_visit;
        $diff = $current_time - $user_time;
        if($diff > 900)
            return false;
        else return true;
    }

    /**
     * Определение онлайн ли пользователь
     */
    public function defOnline(){
        if($this->isOnline())
            return '<div class="offline">Offline</div>';
        else return '<div class="online">Online</div>';
    }

    /**
     * @param bool|false $old
     * @return Event[]
     * Мои события, если $old = true, то вернет прошедшие события
     */
    public function getMyEvents($old = false){
        $current_date = date('Y-m-d H:i:s');
        $events = Event::find()->where(['user_id' => $this->id]);
        if($old == false)
            $events->andWhere("(datetime_to IS NOT NULL AND (datetime_to >= '$current_date'))".
            " OR (datetime_to IS NULL AND (datetime >= '$current_date'))");
        else
            $events->andWhere("(datetime_to IS NOT NULL AND (datetime_to < '$current_date'))".
            " OR (datetime_to IS NULL AND (datetime < '$current_date'))");
        return $events->all();
    }

    public function getMyInvites(){
        $invites = Invite::find()->where(['id_user' => $this->id, 'confirm' => 0]);
        return $invites->all();
    }

    public function getMyInvitesCount(){
        $invites = Invite::find()->where(['id_user' => $this->id, 'confirm' => 0]);
        return $invites->count();
    }

    public function sendInvite($friendId, $eventId){
        $invite = new Invite();
        $invite->id_event = $eventId;
        $invite->id_sender = $this->id;
        $invite->id_user = $friendId;
        return $invite->save();
    }

    public function getFriendsList(){
        $friends = $this->friends;
        return ArrayHelper::map($friends, 'id', 'allname');
    }

    public function sendUpdateMail($text){
    $subject = 'Изменение мероприятия в котором вы участвуете';
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: Timeplace.me <timeplace@timeplace.me>' . "\r\n";
        $text = nl2br($text);
        $text = "<html><head></head><body>$text</body></html>";
        return mail($this->email, $subject, $text, $headers);
    }

    public function getIndexUrl(){
        return Url::to(['user/index', 'id' => $this->id]);
    }

    public function getMessagesUrl(){
        return Url::to(['messages/show']);
    }

    public function getInvitesUrl(){
        return Url::to(['user/invites']);
    }

    public function getFriendsUrl(){
        return Url::to(['user/friends']);
    }

    public function getVkUrl(){
        $url = false;
        if(!empty($this->id_vk)){
            $url = 'https://vk.com/id' . $this->id_vk;
        }elseif(!empty($this->pagevk))
            $url = $this->pagevk;
        return $url;
    }

    public function getMyEventsUrl(){
        return Url::to(['user/myevents']);
    }

    /**
     * Возвращает последнее сообщение в диалоге
     * @return Message
     */
    public function getLastMessage(){
        $messages = $this->message;
        return $messages[count($messages)-1];
    }

    public function writeMessageUrl($isConcrete = false){
        if($isConcrete)
            return Url::to(['messages/create', 'id' => $this->id]);
        else
            return Url::to(['messages/create']);
    }

    /**
     * @return bool
     * Определяет, является ли юзер другом
     */
    public function isFriend(){
        if(!Yii::$app->user->isGuest) {
            $iam = Yii::$app->user->identity;
            if($iam->id == $this->id)
                return true;
            $isFriend = Friend::find()
                ->where(['id_from' => $iam->id, 'id_to' => $this->id])
                ->exists();
            return $isFriend;

        }
        return false;
    }

    /**
     * @return bool|null|Request
     */
    public function isHaveRequestToMe(){
        $iam = Yii::$app->user;
        $request = Request::findOne([
            'id_from' => $this->id,
            'id_to' => $iam->id
        ]);
        if(isset($request))
            return $request;
        return false;
    }

    public function getCityName(){
        if(!empty($this->id_city)){
            return $this->city->name;
        }
        return false;
    }

    public static function getSexList(){
        return [
            0 => 'Мужской',
            1 => 'Женский'
        ];
    }
}

