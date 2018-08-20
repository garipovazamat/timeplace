<?php
use common\models\Category;
use common\models\City;
use common\models\Message;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * @var $lentaEvents \common\models\Event[]
 * @var $signupForm \frontend\models\SignupForm
 * @var $this \yii\web\View
 * @var $loginForm \common\models\LoginForm
 * @var $user \common\models\User
 * @var $searchModel \frontend\models\SearchEvent
 */



\frontend\assets\AppAsset::register($this);

$cloud_cats = Category::getCloudCats();

$main_categories = Category::find()->joinWith('subcategories0')
    ->where('id_main = 1 AND id_sub = id AND id_picture IS NOT NULL')
    ->orderBy([new \yii\db\Expression('-1 * main_priority DESC')])
    ->all();

Yii::$app->session->open();
$fb = new Facebook\Facebook([
    'app_id' => '102474660129796',
    'app_secret' => '31df04a597d8ae6893c6abfb01a9eb0c',
    'default_graph_version' => 'v2.2',
]);
$helper = $fb->getRedirectLoginHelper();
$permissions = ['user_about_me', 'user_birthday', 'user_location', 'email'];
$loginUrl = $helper->getLoginUrl(Url::toRoute(['site/loginfacebook'], true), $permissions);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="ru"> <!--<![endif]-->

<head>

    <meta charset="utf-8">

    <title>TimePlace</title>
    <meta name="description" content="">

    <link rel="shortcut icon" href="/img/favicon/favicon.png" type="image/x-icon">
    <link rel="apple-touch-icon" href="/img/favicon/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/img/favicon/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/img/favicon/apple-touch-icon-114x114.png">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="stylesheet" href="/libs/bootstrap/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="/libs/animate/animate.css">
    <link rel="stylesheet" href="/libs/owl/assets/owl.carousel.css">
    <link rel="stylesheet" href="/libs/Magnific-Popup/magnific-popup.css">
    <link rel="stylesheet" href="/libs/jQueryFormStyler/jquery.formstyler.css">

    <script src="/libs/modernizr/modernizr.js"></script>

    <?php $this->head() ?>

    <link rel="stylesheet" href="css/fonts.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/media.css">
    <link rel="stylesheet" href="css/modif.css">


</head>

<body>
<?php $this->beginBody() ?>
<!-- start шапка-->
<header class="main-header">
    <?=$this->render('_index_header')?>
</header>
<!-- end-->

<!-- start блок слайдера-->
<?=$this->render('_index_slider', [
    'searchModel' => $searchModel,
])?>
<!-- end слайдер-->

<!-- start блок категории-->
<?=$this->render('_index_cats', [
    'cloud_cats' => $cloud_cats,
    'main_categories' => $main_categories,
])?>

<!-- start блок ленты событий-->
<?=$this->render('_index_lenta', [
    'lentaEvents' => $lentaEvents,
])?>
<!-- end-->

<!-- start подвал-->
<footer id="main-footer" class="main-footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-12">
                <a class="logo" href="/"><img src="img/logo.png" height="44" width="44" alt="TimePlace"></a>
                <p>Все права защищены &copy; TimePlace.me 2016</p>
            </div>
            <div class="col-lg-5 col-md-12">
                <ul>
                    <li><a href="#">Реклама</a></li>
                    <li><a href="#">Контакты</a></li>
                    <li><a href="#">Партнерам</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-12 right-footer">
                <p>Дизайн сайта: <a href="http://waxwing-studio.ru/">waxwing-studio.ru</a></p>
            </div>
        </div>
    </div>
</footer>
<!-- конец подвала-->

<!-- форма выбора города-->
<?=$this->render('_form_city_choose')?>

<!-- форма регистрации-->
<?=$this->render('_form_signup', [
    'signupForm' => $signupForm,
    'loginUrl' => $loginUrl
])?>

<!-- форма входа-->
<?=$this->render('_index_login', [
    'loginForm' => $loginForm,
    'loginUrl' => $loginUrl
])?>

<?php $this->endBody() ?>

<!--[if lt IE 9]>
<script src="libs/html5shiv/es5-shim.min.js"></script>
<script src="libs/html5shiv/html5shiv.min.js"></script>
<script src="libs/html5shiv/html5shiv-printshiv.min.js"></script>
<script src="libs/respond/respond.min.js"></script>
<![endif]-->

<script src="libs/waypoints/waypoints.min.js"></script>
<script src="libs/animate/animate-css.js"></script>
<script src="libs/plugins-scroll/plugins-scroll.js"></script>
<script src="libs/owl/owl.carousel.min.js"></script>
<script src="libs/jQueryFormStyler/jquery.formstyler.min.js"></script>

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
<?php $this->endPage() ?>