<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 06.11.2015
 * Time: 11:14
 */
use common\models\Category;
use yii\helpers\Url;

/**
 * @var integer $id
 * @var $oneSubcategory common\models\Category;
**/

$subcategories = Category::find()->joinWith('subcategories0')->
    where("id_main = $id AND id_sub = id")->all();

foreach($subcategories as $oneSubcategory){
    //print_r($oneSubcategory->getPopularEvents(2));
    ?>
    <div class="subcat">
    <a href=<?=Url::to(['event/index', 'category' => $oneSubcategory['id']])?>>
        <div class="onesubcat">
            <?=$oneSubcategory['name']?>
        </div>
    </a>
    <div class="popular_events">
        <?php
        $popEvents = $oneSubcategory->getPopularEvents(4);
        foreach($popEvents as $oneEvent){
                echo "<img src = '" . $oneEvent->getOnePicture()->getBigMiniaturePath() . "'>";
        }
        ?>
    </div>
    </div>
<?}?>
