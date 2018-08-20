<?php
/**
 * Created by PhpStorm.
 * User: Azamat
 * Date: 23.01.2016
 * Time: 14:16
 * @var $this yii\web\View
 * @var $oneEvent Event
 */
use common\models\Event;
use yii\helpers\Url;
use yii\helpers\Html;
$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyA4t1iQVtgKE7qSaPI6xmtlIvHZzs34_DY&callback=initMap&libraries=places');
$daysCount = 7;
$events = Event::find()->where("(datetime > NOW() OR datetime_to > NOW()) AND datetime < (NOW() + INTERVAL $daysCount DAY) AND moderated = 1")->all();
$this->title = "Все события на карте на ближайшие $daysCount дней";
?>
<h3><?= Html::encode($this->title) ?></h3>
<div id="map"></div>

<script>
    function initMap() {
        var map;
        var position;
        navigator.geolocation.getCurrentPosition(function (cPosition) {
            position = cPosition;
        });
        var cZoom = 13;
        if(!position){
            position = {
                coords : {latitude : 55.09, longitude : 61.24}
            };
            cZoom = 8;
        }

            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;
            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: latitude, lng: longitude},
                zoom: cZoom
            });
            <?
            $i = 0;
            foreach($events as $oneEvent){
                if(!empty($oneEvent->coordinates)){
                $coords = explode(',', $oneEvent->coordinates);
                ?>
            var marker = new google.maps.Marker({
                position: {lat: <?=$coords[1]?>, lng: <?=$coords[0]?>},
                map: map,
                title: '<?=$oneEvent->name?>'
            });
            marker.addListener('click', function () {
                window.location = '<?=$oneEvent->getUrl() ?>';
            });
            marker.setMap(map);
            <?
            $i++;
            }} ?>
    }
</script>
