<?php
use common\models\City;
$datetime = '2015-12-29 16:00:00';
$user = Yii::$app->user->identity;
$tz = \common\models\Timezone::findOne(['id' => $user->id_tz]);
$server_tz = date('Z');
$user_tz = $tz->utc*3600;
$difference = $server_tz - $user_tz;
$datetime = strtotime($datetime) + $difference;
print_r(date('Y-m-d H:i:s', $datetime));
?>