<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 10.04.2016
 * Time: 18:49
 *
 * @var $choosen_cat_id integer
 */

use common\models\Category;
use common\models\Event;

$mainCats = Category::getMainCats();
$choosen_category = Category::findById($choosen_cat_id);
$first_level_cat = $choosen_category->getLevelCat(1);
?>
<div class="container tabs-cat">
    <ul class="poisk-category">
        <?php foreach($mainCats as $oneCat){?>
        <a href="<?=$oneCat->getCatUrl()?>">
            <li class="name-cat <?=($oneCat->id == $first_level_cat->id) ? 'firstshow' : ''?>" >
                <?=$oneCat->name ?>
            </li>
        </a>
        <?php } ?>
    </ul>
    <div class="tabs_list">
        <?php foreach($mainCats as $oneCat){
            $subCats = $oneCat->thisSubcategory();
            ?>
            <ul class="wrap_tabs_content">
                <?php foreach($subCats as $oneSubcat){?>
                    <li><a href="<?=$oneSubcat->getCatUrl()?>"><?=$oneSubcat->name?></a></li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>
</div>