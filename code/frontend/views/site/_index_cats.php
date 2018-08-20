<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 23.03.2016
 * Time: 11:42
 *
 *
 * @var $cloud_cats \common\models\Category[]
 */

use common\models\Picture;
use yii\helpers\Url;
?>
<section id="categories" class="categories">
    <div class="cat">
        <div class="container wrap-cat">
            <h2>Категории</h2>
        </div>
    </div>
    <div class="container wrap-categories">
        <!-- start категории в круге-->
        <div class="row">
            <? foreach($cloud_cats as $oneCategory){
    ?>
    <div class="col-xs-6 col-sm-3 col-md-2 item-cat item-cat1 hidden-xs hidden-sm">
        <div class="circle circle1" style="background-image:  url('<?=$oneCategory->getCloudImage()?>')"></div>
        <a class="name-cat" href="<?=Url::toRoute(['event/index', 'category' => $oneCategory['id']])?>">
            <h4><?=$oneCategory->name?></h4>
        </a>
    </div>
<?}?>
</div>
<!-- end-->
<!-- start категории в квадрате-->
<div class="square-cat">
    <div class="row">
        <? foreach($main_categories as $oneCategory){
            $cur_picture = Picture::findOne(['id' => $oneCategory->id_picture]);
            $subcats = $oneCategory->thisSubcategory(5);
            ?>
            <div class="col-md-3 wrap-item-sq-cat">
                <div class="item-sq-cat item-sq-cat1"
                     style="background-image: url('<?=$cur_picture->getCategoryImagePath()?>')">
                    <h3><a href="<?=Url::toRoute(['event/index', 'category' => $one_subcat['id']])?>?>">
                            <?=$oneCategory->name ?>
                        </a></h3>
                    <ul>
                        <?php foreach ($subcats as $one_subcat) {?>
                            <li><a href="<?=Url::toRoute(['event/index', 'category' => $one_subcat['id']])?>">
                                    <?=$one_subcat->name ?>
                                </a></li>
                        <?}?>
                    </ul>
                </div>
            </div>
        <?}?>
    </div>
</div>
<!-- end  категории в квадрате-->
</div>
</section>