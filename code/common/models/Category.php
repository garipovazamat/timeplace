<?php

namespace common\models;

use Yii;
use yii\bootstrap\Nav;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property integer $id_picture
 * @property integer $id_micropicture
 * @property boolean $in_cloud
 * @property integer $priority
 * @property integer $main_priority
 * @property string $name
 *
 * @property Picture $idPicture
 * @property EventCategory[] $eventCategories
 * @property Subcategory[] $subcategories
 * @property Subcategory[] $subcategories0
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_picture'], 'integer'],
            [['id_micropicture'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
            ['in_cloud', 'safe'],
            [['priority', 'main_priority'], 'integer', 'min' => 0]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_picture' => 'Картинка',
            'name' => 'Название',
            'id_micropicture' => 'Миниатюра',
            'in_cloud' => 'В облаке',
            'priority' => 'Приоритет для облака',
            'main_priority' => 'Приоритет для главной страницы'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPicture()
    {
        return $this->hasOne(Picture::className(), ['id' => 'id_picture']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventCategories()
    {
        return $this->hasMany(EventCategory::className(), ['id_category' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubcategories()
    {
        return $this->hasMany(Subcategory::className(), ['id_main' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubcategories0()
    {
        return $this->hasMany(Subcategory::className(), ['id_sub' => 'id']);
    }

    /**
     * @param $id
     * @return null|Category
     */
    public static function findById($id){
        return Category::findOne(['id' => $id]);
    }

    public static function createSubcategory($count = 1){
        $mass = [];
        for($i=0; $i<$count; $i++){
            $mass[$i] = new Subcategory();
        }
        return $mass;
    }

    public function subcategoriesList(){
        if ($this->isNewRecord) {
            $categories = Category::find()->all();
        }
        else{
            $categories = Category::find()->where("id != $this->id")->all();
        }
        return ArrayHelper::map($categories, 'id', 'name');
    }

    public static function categoryList($without_main = false){
        if($without_main)
            $categories = Category::find()->where('id != 1')->all();
        else
            $categories = Category::find()->all();
        return ArrayHelper::map($categories, 'id' , 'name');
    }

    /**
     * Возвращает цепочку из категорий по вложенности, начиная с самой глубокой
     * @return Category[]
     */
    public function getChain(){
        $super_main = Category::findOne(['id' => '1']);
        $current_sub_category = $this->id;
        $chain = [];
        while ($current_sub_category != $super_main->id){
            $current_child = Subcategory::findOne(['id_sub' => $current_sub_category]);
            $id_child = $current_child['id_sub'];
            $id_main = $current_child['id_main'];
            $chain[] = [$id_main , $id_child];
            $current_sub_category = $id_main;
        }
        return $chain;
    }


    //Возвращается запрос для получения мероприятий на каждую подкатегорию внутри текущей
    /**
     * @return \yii\db\ActiveQuery
     */
    public function findAllEventsQuery(){
        $subcats = $this->findAllSubcategories();
        $cats_array = [$this->id];
        foreach ($subcats as $one_subcat){
            $cats_array[] = $one_subcat['id'];
        }
        $query = Event::find()->joinWith('eventCategories')->where(['id_category' => $cats_array]);
        return $query;
    }

    /**
     * Возвращает сами мероприятия
     */
    public function findAllEvents(){
        $query = $this->findAllEventsQuery();
        return $query->all();
    }

    /**
     * @return Event[]
     * Возвращает мероприятия только для текущей категории
     */
    public function findThisEvents(){
        $event_categories = EventCategory::find()->where(['id_category' => $this->id])->all();
        $events = [];
        foreach($event_categories as $one_event_cat)
            $events[] = Event::findOne(['id' => $one_event_cat->id_event]);
        return $events;
    }

    /**
     * @param $id_category
     * @param $subcatigories
     * Записывает в переменную $subcatigories все категории
     * которые находятся в категории с id $id_category и вложенные далее
     */
    public static function insideCategory($id_category, &$subcatigories){
        $subcats = Subcategory::find()->where(['id_main' => $id_category])->all();
        foreach($subcats as $one_subcat){
            $subcatigories[] = Category::findOne(['id' => $one_subcat['id_sub']]);
            self::insideCategory($one_subcat['id_sub'], $subcatigories);
        }
    }

    //Возвращает массив объектов "Category" которые вложены в текущую категорию
    public function findAllSubcategories(){
        $subcatigories = [];
        self::insideCategory($this->id, $subcatigories);
        return $subcatigories;
    }

    /**
     * @param $count
     * @return Event[]
     * Возвращает популярные мероприятия в категории где $count - количество возвращаемых мероприятий
     */
    public function getPopularEvents($count){
        $events = $this->findAllEventsQuery()->orderBy(['visits' => SORT_DESC])->limit($count)->all();
        return $events;
    }

    /**
     * @param $parent_id
     * @param bool|false $controller
     * @return array
     * Если $controller = true то возвращает массив в форме ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
     * иначе в форме ['<id>' => '<name>']
     */
    public static function depCategoryList($parent_id, $controller = false){
        $subcats = [];
        $subcategogy = Subcategory::find()->where(['id_main' => $parent_id])->all();
        if($controller) {
            foreach ($subcategogy as $one_subcat) {
                $subcats[] = ['id' => $one_subcat->sub->id, 'name' => $one_subcat->sub->name];
            }
        }
        else{
            foreach ($subcategogy as $one_subcat) {
                $subcats[] = $one_subcat->sub;
            }
            $subcats = ArrayHelper::map($subcats, 'id' , 'name');
        }
        return $subcats;
    }

    /**
     * Получает подкатегории текущей категории
     * @return Category[]
     */
    public function thisSubcategory($count = 0){
        $cats = [];
        $subcats = Subcategory::find()
            ->joinWith('sub')
            ->where(['id_main' => $this->id])
            ->orderBy([new \yii\db\Expression('-1 * main_priority DESC')]);
        if($count != 0)
            $subcats->limit($count);
        $subcats = $subcats->all();
        foreach($subcats as $one_subcat)
            $cats[] = Category::findOne(['id' => $one_subcat->id_sub]);
        return $cats;
    }

    /**
     * @return Category[]
     *
     */
    public static function getMainCats(){
        $main_cat = Category::findOne(['id' => 1]);
        return $main_cat->thisSubcategory();
    }

    /**
     * @return string
     * @throws \Exception
     * Возвращает html код навигационного меню bootstrap
     */
    public function getCatsNavBar(){
        $items = [];
        $cats = $this->thisSubcategory();
        foreach($cats as $one_cat){
            $items[] = [
                'label' => $one_cat->name,
                'url' => Url::to(['event/index', 'category' => $one_cat->id])
            ];
        }
        return Nav::widget([
            'options' => [
                'class' => 'nav nav-pills'
            ],
            'items' => $items
        ]);
    }
    /*public function beforeDelete(){
        $all_events = $this->findThisEvents();
        foreach($all_events as $one_event) {
            $one_event->delete();
            echo 'Удалено событие';
        }
        $all_subcats = Category::findOne(['id' =>$this->id])->thisSubcategory();
        foreach($all_subcats as $one_subcat) {
            $one_subcat->delete();
            echo 'Удалена подкатегория';
        }
        echo 'подкатегории удалены';
        $deleting = Yii::$app->db;
        $deleting->createCommand()
            ->delete('subcategory', "id_main =$this->id OR id_sub = $this->id")->execute();

    }*/
    public function beforeDelete(){
        $events = $this->findThisEvents();
        EventCategory::deleteAll(['id_category' => $this->id]);
        foreach($events as $one_event)
            $one_event->delete();
        $cats = $this->thisSubcategory();
        Subcategory::deleteAll(['id_sub' => $this->id]);
        foreach($cats as $one_cat)
            $one_cat->delete();
        return true;
    }

    public static function getCloudCats(){
        $categories = Category::find()->where(['in_cloud' => 1])
            ->orderBy([new \yii\db\Expression('-1 * priority DESC')])
            ->all();
        return $categories;
    }

    public static function getCloud(){
        /**
         * @var $micropic Picture
         */
        $text = '<div class="category_with_micro">';
        $categories = self::getCloudCats();
        foreach($categories as $oneCat){
            $micropic = Picture::findOne(['id' => $oneCat->id_micropicture]);
            $imagePath = (isset($micropic)) ? $micropic->getCategoryImagePath() : '';
            $text = $text . '<div class="onecat_with_micro">' . '<a href="' .
                Url::toRoute(['event/index', 'category' => $oneCat['id']]) .
                '"><img src="' .
                $imagePath .
                '">' . $oneCat->name . '</a>' . '</div>';
        }
        $text = $text . '</div>';
        return $text;
    }

    public function getCatUrl(){
        return Url::toRoute(['event/index', 'category' => $this->id]);
    }

    /**
     * @return bool|null|string
     * Возвращает картинку категории в облаке
     * @var $picture Picture
     */
    public function getCloudImage(){
        if(!$this->in_cloud)
            return false;
        $picture = Picture::findOne(['id' => $this->id_micropicture]);
        $url = false;
        if(isset($picture)){
            $url = $picture->getImagePath();
        }
        return $url;
    }

    /**
     * Возвращает уровень вложенности категории, 0 - главная
     * @return int
     */
    public function getCurrentLevel(){
        $chain = $this->getChain();
        $chain_length = count($chain);
        return $chain_length;
    }

    /**
     * @return Category|null
     */
    public function getOverCategory(){
        /** @var $current_child Subcategory*/
        $current_child = Subcategory::findOne(['id_sub' => $this->id]);
        $parent = Category::findById($current_child->id_main);
        return $parent;
    }

    /**
     * @param $level
     * @return Category|bool
     */
    public function getLevelCat($level){
        $cat = $this;
        $current_level = $this->getCurrentLevel();
        if($current_level < $level)
            return false;
        while($current_level > $level){
            $cat = $cat->getOverCategory();
            $current_level--;
        }
        return $cat;
    }
}
