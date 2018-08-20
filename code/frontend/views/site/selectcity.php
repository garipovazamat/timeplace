<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 24.01.2016
 * Time: 16:07
 */

$countCity = City::find()
    ->where(['id_country' => $id_country])
    ->count();
$cities = City::cityList($id_country);
if($countCity>0){
    foreach($cities as $city)
        echo "<option value='".$city->id_city."'>".$city->name."</option>";
}
else{
    echo "<option>-</option>";
}
?>