<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 20.04.2016
 * Time: 19:03
 * @var $paginator \yii\data\Pagination
 * @var $type integer
 */
use yii\helpers\Url;


$type_user = 1;
$type_event = 2;


$current_page = $paginator->page;
$firstNumbet = 0;
if($current_page < 6)
    $firstNumbet = 0;
else{
    $firstNumbet = $current_page - 5;
}

if($paginator->pageCount <= 10)
    $lastNumber = $paginator->pageCount;
else{
    $lastNumber = $current_page + 5;
}
?>

<div class="pagination-wrap">
    <ul class="pagination">
        <?php
        if($type == $type_user){
            for($i = $firstNumbet; $i < $lastNumber; $i++) {
                if ($paginator->getPage() == $i)
                    echo '<li class="active"><a href="#">' . ($i + 1) . '</a></li>';
                else
                    echo '<li><a class="number" href="' . Url::to(['user/search', 'page' => $i]) . '">' . ($i + 1) . '</a></li>';
            }
        }

        if($type == $type_event){
            for($i = $firstNumbet; $i < $lastNumber; $i++) {
                if ($paginator->getPage() == $i)
                    echo '<li class="active">' . ($i + 1) . '</li>';
                else
                    echo '<li><a class="number" href="' . Url::to(['event/index', 'page' => $i]) . '">' . ($i + 1) . '</a></li>';
            }
        }
        ?>
    </ul>
</div>