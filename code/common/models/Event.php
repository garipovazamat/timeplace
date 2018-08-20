<?php

namespace common\models;

use nepstor\validators\DateTimeCompareValidator;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "event".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $fulldesc
 * @property string $shortdesc
 * @property string $datetime
 * @property string $place
 * @property integer $opened
 * @property integer $city_id
 * @property integer $moderated
 * @priperty string $coordinates
 * @property string $datetime_to
 * @property float $cost
 * @property Comment[] $comments
 * @property User $user
 * @property EventCategory[] $eventCategories
 * @property EventUser[] $eventUsers
 * @property PictureEvent[] $pictureEvents
 * @property City $city
 */
class Event extends \yii\db\ActiveRecord
{

    const INTERVAL = 14;

    const NO_MODERATED = 0;
    const MODERATED_YES = 1;
    const MODERATED_NO = 2;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event';
    }

    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            [['user_id', 'datetime', 'city_id', 'name'], 'required'],
            [['user_id', 'city_id', 'moderated'], 'integer'],
            [['fulldesc'], 'string', 'max' => 2000],
            //[['datetime', 'datetime_to'], 'safe'],
            [['name', 'place'], 'string', 'max' => 250],
            ['coordinates', 'default'],
            //['datetime', DateTimeCompareValidator::className(), 'compareAttribute' => 'datetime_to', 'format' => 'Y-m-d H:i', 'operator' => '>='],
            ['datetime_to', 'validateDate'],
            ['datetime', 'dateEarly'],
            ['cost', 'default'],
            ['opened', 'default', 'value' => 1],
        ];
    }

    public function validateDate($attribute, $params)
    {
        if (!empty($this->datetime_to)) {
            if (strtotime($this->datetime) > strtotime($this->datetime_to))
                $this->addError($attribute, 'Дата окончания раньше начала');
            $interval = strtotime($this->datetime_to) - strtotime($this->datetime);
            if($interval > (self::INTERVAL * 24 * 60 * 60)){
                $this->addError($attribute, 'Длительность события должна быть менее 2 недель');
            }
        }
    }

    public function dateEarly($attribute)
    {
        if (strtotime($this->datetime) < strtotime(User::getMyDatetime(date('Y-m-d H:i:s'))))
            $this->addError($attribute, 'Дата уже прошла!');
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Создатель мероприятия',
            'name' => 'Название',
            'fulldesc' => 'Описание',
            'shortdesc' => 'Краткое описание',
            'datetime' => 'Дата и время начала проведения',
            'place' => 'Место (в указанном городе)',
            'city_id' => 'Город',
            'moderated' => 'Прошел модерацию',
            'datetime_to' => 'Дата и время окончания',
            'coordinates' => 'Отметьте на карте',
            'cost' => 'Стоимость',
            'opened' => 'Тип события'
        ];

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['id_event' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventCategories()
    {
        return $this->hasMany(EventCategory::className(), ['id_event' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventUsers()
    {
        return $this->hasMany(EventUser::className(), ['id_event' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPictureEvents()
    {
        return $this->hasMany(PictureEvent::className(), ['id_event' => 'id']);
    }

    public function getCity()
    {
        return $this->hasOne(City::className(), ['id_city' => 'city_id']);
    }

    /**
     * Возвращает массив картинок в мероприятии
     * @return Picture[]
     */
    public function getPictures()
    {
        $picture = Picture::find()
            ->joinWith('pictureEvents')
            ->where(['id_event' => $this->id])
            ->all();
        return $picture;
    }

    // Возвращает массив из $n картинок в мероприятии, в случае отсутствия их, создает новые
    public function getPicturesForUpload($n)
    {
        if (!$this->isNewRecord) {
            $picture = Picture::find()
                ->joinWith('pictureEvents')
                ->where(['id_event' => $this->id])
                ->all();
            $count = count($picture);
            if ($count < $n) {
                for ($i = $count; $i < $n; $i++) {
                    $picture[] = new Picture();
                }
            }
        } else {
            $picture = [];
            for ($i = 0; $i < $n; $i++)
                $picture[] = new Picture();
        }
        return $picture;
    }

    /**
     * @return Picture
     * Возвращает одну картинку(первую) из описания мероприятия
     */
    public function getOnePicture()
    {
        $pictureEvent = PictureEvent::find()
            ->where(['id_event' => $this->id])
            ->orderBy(['date_add' => SORT_DESC])
            ->one();
        $picture = Picture::findOne(['id' => $pictureEvent['id_picture']]);
        if (isset($picture))
            return $picture;
        else return false;
    }

    public function getLentaThumbnail(){
        $picture = $this->getOnePicture();
        if(!empty($picture)){
            return $picture->getLentaThumbnail();
        }
        return 'img/lenta.png';
    }

    public function findAllComments()
    {
        $comments = Comment::find()
            ->where(['id_event' => $this->id])
            ->orderBy(['date_add' => SORT_ASC])
            ->all();
        return $comments;
    }

    //Определяет, участвует ли пользователь в мероприятии
    public function isUserRun($user_id)
    {
        $event_user = EventUser::findOne(['id_user' => $user_id, 'id_event' => $this->id]);
        if (isset($event_user))
            return true;
        else
            return false;
    }

    //Получение кнопки удаления мероприятия
    public function getDeleteButton()
    {
        if (!Yii::$app->user->isGuest) {
            if ($this->user_id == Yii::$app->user->id) {
                return Html::a('Удалить', ['delete', 'id' => $this->id], [
                    'data' => [
                        'confirm' => 'Вы действительно хотите удалить мероприятие?',
                        'method' => 'post',
                    ],
                ]);
            }
        } else {
            return false;
        }
    }

    //Получение кнопки участия в мероприятии
    public function getRunButton()
    {
        if (!Yii::$app->user->isGuest) {
            if (!$this->isUserRun(Yii::$app->user->id)) {
                return Html::a('Пойду', ['run', 'id' => $this->id], [
                    'class' => 'btn red_btn',
                ]);
            } else {
                return Html::a('Не пойду', ['runout', 'id' => $this->id], [
                    'class' => 'btn red_btn',
                ]);
            }
        }
        return false;
    }

    /**
     * @return bool|string
     * Возвращает кнопку, которая пересылает на страницу с друзьями, которых ещё не приглпсили
     */
    public function getInviteButton()
    {
        if (Yii::$app->user->isGuest)
            return false;
        if ($this->isEnd())
            return false;

        $user_id = Yii::$app->user->id;
        return Html::a('Пригласить друзей', ['user/friends', 'invite_event' => $this->id],
            ['class' => 'Invite-friends',]
        );
    }


    public
    function getEventUrl()
    {
        return Url::to(['event/view', 'id' => $this->id], true);
    }

    /**
     * Возвращает массив участников мероприятия
     * @return User[]
     */
    public
    function getMembers()
    {
        $members = $this->eventUsers;
        $users = [];
        foreach ($members as $one_member) {
            $users[] = User::findOne(['id' => $one_member['id_user']]);
        }
        return $users;
    }

    /**
     * @return bool
     * Отправка с уведомлением об обновлении всем пользователям
     */
    public
    function sendUpdateMessage()
    {
        $text = "Мероприятие \"$this->name\" " .
            Html::a('[страница мероприятия]', $this->getEventUrl(), ['data-pjax' => 0]) .
            " было изменено.";
        $members = $this->getMembers();
        $control = true;
        foreach ($members as $one_member) {
            $text_with_dear = "Уважаемый $one_member->allname! \n" . $text;
            if (!Message::sendMessage($this->user_id, $one_member->id, $text_with_dear))
                $control = false;
            if(!$one_member->sendUpdateMail($text_with_dear))
                $control = false;
        }
        return $control;
    }



    public
    function getShortDesc($count = 200)
    {
        //$name = "<b>" . $this->name . "</b>";
        $desc = mb_substr($this->fulldesc, 0, $count, 'UTF-8');
        return $desc;
    }

    public
    function beforeDelete()
    {
        EventCategory::deleteAll(['id_event' => $this->id]);
        EventUser::deleteAll(['id_event' => $this->id]);
        $pictures = Picture::find()
            ->joinWith('pictureEvents')
            ->where(['id_event' => $this->id])
            ->all();
        PictureEvent::deleteAll(['id_event' => $this->id]);
        foreach ($pictures as $one_picture)
            $one_picture->delete();
        Comment::deleteAll(['id_event' => $this->id]);
        return true;
    }

    public
    function beforeSave()
    {
        $this->fulldesc = strip_tags($this->fulldesc);
        $this->fulldesc = nl2br($this->fulldesc);
        //$this->datetime = date('Y-m-d H:i:s', strtotime($this->datetime));

        return true;
    }

    /**
     * @return Category
     * Возвращает текущую категорию, в которой находится мероприятие
     */
    public
    function getThisCat()
    {
        $event_cat = EventCategory::findOne(['id_event' => $this->id]);
        if (isset($event_cat))
            $cat = Category::findOne(['id' => $event_cat->id_category]);
        else $cat = Category::findOne(['id' => 1]);
        return $cat;
    }

    public
    function getThisEventCats()
    {
        $cat = $this->getThisCat();
        return $cat->getCatsNavBar();
    }

    public
    function getLikeButton()
    {
        if (!Yii::$app->user->isGuest) {
            $count = Likes::find()->where(['id_event' => $this->id])->count();
            $user_id = Yii::$app->user->id;
            $myLike = Likes::find()->where(['id_event' => $this->id, 'id_user' => $user_id])->count();
            if ($myLike == 0)
                $link = ['like', 'id' => $this->id];
            else
                $link = ['likeout', 'id' => $this->id];
            return Html::a('<div class="like"><span class="number-info-event">'.$count.'</span></div>',
                $link);
        } else return false;
    }

    /**
     * @return int
     * Возвращает разницу между текущей датой и датой начала мероприятия в формате time()
     */
    public
    function diffTime()
    {
        $curentTime = time();
        $endTime = $this->datetime;
        $diffTime = $endTime - $curentTime;
        if ($diffTime > 0)
            return $diffTime;
        else return 0;
    }

    public
    function isStart()
    {
        $current_time = time();
        $event_start_time = strtotime($this->datetime);
        if ($current_time > $event_start_time)
            return true;
        else return false;
    }

    public
    function isNotEndAndStart()
    {
        $current_time = time();
        if (!empty($this->datetime_to))
            $event_end_time = strtotime($this->datetime_to);
        else $event_end_time = strtotime($this->datetime);
        if ($current_time < $event_end_time && $this->isStart())
            return true;
        else return false;
    }

    public function isEnd(){
        $current_time = time();
        if (!empty($this->datetime_to))
            $event_end_time = strtotime($this->datetime_to);
        else $event_end_time = strtotime($this->datetime);
        if ($current_time > $event_end_time)
            return true;
        else return false;
    }

    public function getInviteFriends(){
        $user = Yii::$app->user->identity;
        $sended_invites = Invite::find()->where(['id_event' => $this->id])->all();
        $all_friends = $user->friends;
        $out_friends_id = [];
        $friends_id = [];
        foreach ($sended_invites as $one_sender_inv)
            $out_friends_id[] = $one_sender_inv->id_user;
        foreach ($all_friends as $one_friend)
            $friends_id[] = $one_friend->id;

        $friends = User::find()->where(['not', ['in', 'id', $out_friends_id]])
            ->andWhere(['in', 'id', $friends_id]);
        return $friends->all();
    }

    /**
     * Определяет, участвует ли пользователь в этом мероприятии
     */
    public function isInvolved($user_id){
        $eventUser = EventUser::findOne(['id_user' => $user_id, 'id_event' => $this->id]);
        if($eventUser)
            return true;
        else return false;
    }

    public function getUrl(){
        return Url::to(['event/view', 'id' => $this->id]);
    }

    public function getCat(){
        $eventCat = EventCategory::findOne(['id_event' => $this->id]);
        $cat = $eventCat->idCategory;
        return $cat;
    }

    public function getUserCount(){
        $count = EventUser::find()->where(['id_event' => $this->id])->count();
        return $count;
    }

    public static function getIndexUrl(){
        return Url::to(['event/index']);
    }

    public static function getCreateUrl(){
        return Url::to(['event/create']);
    }

    public static function getMapUrl(){
        return Url::to(['event/map']);
    }

    public function getMiniatureUrl(){
        if(!empty($this->getOnePicture()))
            return $this->getOnePicture()->getMiniaturePath();
        else return false;
    }
    public function getBigMiniatureUrl(){
        if(!empty($this->getOnePicture()))
            return $this->getOnePicture()->getBigMiniaturePath();
        else return false;
    }

    public function isOwner(){
        if(!Yii::$app->user->isGuest){
            if(Yii::$app->user->id == $this->user_id)
                return true;
        }
        return false;
    }

    public function getUpdateUrl(){
        return Url::to(['event/update', 'id' => $this->id]);
    }
}