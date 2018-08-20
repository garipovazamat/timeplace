<?php

/** @var $this \yii\web\View
 * @var $content string
 * @var $user \common\models\User
 * @var $cloud_cats \common\models\Category[]
 */

use common\models\City;
use common\models\Message;
use common\models\Category;
use yii\helpers\Url;
use frontend\assets\AppAsset;

if(Yii::$app->session->has('id_city')) {
    $city = City::getSessionCity();
    $cityName = $city->name;
} else $cityName = 'Выберите город';

AppAsset::register($this);

if(!Yii::$app->user->isGuest) {
    $user = Yii::$app->user->identity;
    $user->setLastVisit();
}

$cloud_cats = Category::getCloudCats();

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="ru"> <!--<![endif]-->

<head>

    <meta charset="utf-8">

    <title><?=$this->title?></title>
    <meta name="description" content="">

    <link rel="shortcut icon" href="img/favicon/favicon.png" type="image/x-icon">
    <link rel="apple-touch-icon" href="img/favicon/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="img/favicon/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="img/favicon/apple-touch-icon-114x114.png">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="stylesheet" href="libs/bootstrap/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="libs/animate/animate.css">
    <link rel="stylesheet" href="libs/owl/assets/owl.carousel.css">
    <link rel="stylesheet" href="libs/Magnific-Popup/magnific-popup.css">
    <link rel="stylesheet" href="libs/jQueryFormStyler/jquery.formstyler.css">

    <!--<link rel="stylesheet" href="css/fonts.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/media.css">!-->

    <script src="libs/modernizr/modernizr.js"></script>

    <?php $this->head() ?>

</head>

<body>
<?php $this->beginBody() ?>
<div id="wrapper">
    <div class="container">
        <aside id="navbar" class="layout-navbar">
            <div class="logo-city">
                <a class="logo" href="/"><img src="img/logo.png" height="45" width="45" alt="TimePlace"></a>
                <a class="city open-popup" href="#select-city"><?=$cityName?></a>
            </div>
            <?php if(!Yii::$app->user->isGuest){?>
            <div class="aftor clearfix">
                <!-- start аватар пользователя-->
                <div class="aftor-avatar">
                    <img src="<?=$user->getPhoto()->getMiniaturePath()?>" alt="">
                </div>
                <!-- start вывод пользователя-->
					<span class="my-page">
						<a href="<?=$user->getIndexUrl()?>"><?=$user->allname?></a>
					</span>
                <a href="#" class="arow-mnu clearfix"><span></span></a>
            </div>
            <ul class="menu-aftor">
                <li><a href="<?=$user->getIndexUrl()?>">Моя Страница</a></li>
                <li><a href="<?=$user->getMessagesUrl()?>">Сообщения <span class="num-aftor">
                            <?=Message::getNewMessCount()?>
                        </span></a></li>
                <li><a href="<?=$user->getMyEventsUrl()?>">Мои события</a></li>
                <li><a href="<?=$user->getInvitesUrl()?>">Приглашения</a></li>
                <li>
                    <a href="<?=$user->getFriendsUrl()?>">Друзья</a>
                    <?=$this->render('_search_friend_form') ?>
                </li>
                <li><a href="<?=Url::to(['user/edit'])?>">Настройки</a></li>
                <li><a href="<?=Url::to(['/site/logout'])?>">Выход</a></li>
            </ul>
            <a class="new-event" href="<?=Url::to(['event/create'])?>">Создать событие</a>
            <?php } ?>
            <div class="aside-category">
                <?php foreach($cloud_cats as $oneCat){?>
                <a class="name-cat" href="<?=$oneCat->getCatUrl()?>">
                    <?=$oneCat->name ?>
                </a>
                <?php } ?>
            </div>
        </aside>
        <div  class="main_body">
            <div class="tablet-panel">
                <a href="#" data-toggle=".main_body" class="toggle-menu clearfix"><span></span></a>
                <div class="logo-city">
                    <a class="logo" href="/"><img src="img/logo.png" height="45" width="45" alt="TimePlace"></a>
                    <a class="city open-popup" href="#select-city"><?=$cityName?></a>
                </div>
            </div>
            <?=$content?>
        </div>
        <footer class="footer_wrapper">
            <div class="row">
                <div class="col-md-4">
                    <a class="logo" href="/"><img src="img/logo.png" height="44" width="44" alt="TimePlace"></a>
                    <p>Все права защищены &copy; TimePlace.me 2016</p>
                </div>
                <div class="col-md-4">
                    <ul>
                        <li><a href="#">Реклама</a></li>
                        <li><a href="#">Контакты</a></li>
                        <li><a href="#">Партнерам</a></li>
                    </ul>
                </div>
                <div class="col-md-4 right-footer">
                    <p>Дизайн сайта: <a href="http://waxwing-studio.ru/">waxwing-studio.ru</a></p>
                </div>
            </div>
        </footer>
    </div>
</div>

<!-- прелоадер-->
<div class="loader">
    <div class="loader_inner"></div>
</div>

<!-- форма выбора города-->
<?=$this->render('/site/_form_city_choose')?>

<?php $this->endBody() ?>



<!— Yandex.Metrika counter —>
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter34738740 = new Ya.Metrika({
                    id:34738740,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true,
                    ut:"noindex"
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/34738740?ut=noindex" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!— /Yandex.Metrika counter —>

<!— Yandex.Metrika counter —>
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter34738740 = new Ya.Metrika({
                    id:34738740,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true,
                    ut:"noindex"
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/34738740?ut=noindex" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!— /Yandex.Metrika counter —>

</body>


<!--[if lt IE 9]>
<script src="libs/html5shiv/es5-shim.min.js"></script>
<script src="libs/html5shiv/html5shiv.min.js"></script>
<script src="libs/html5shiv/html5shiv-printshiv.min.js"></script>
<script src="libs/respond/respond.min.js"></script>
<![endif]-->

<!--<script src="libs/jquery/jquery-1.11.2.min.js"></script>
<script src="libs/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>!-->
<script src="libs/waypoints/waypoints.min.js"></script>
<script src="libs/animate/animate-css.js"></script>
<script src="libs/plugins-scroll/plugins-scroll.js"></script>
<script src="libs/owl/owl.carousel.min.js"></script>
<script src="libs/jQueryFormStyler/jquery.formstyler.min.js"></script>

</html>
<?php $this->endPage() ?>