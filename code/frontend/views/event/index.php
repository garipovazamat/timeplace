<?php
/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $searchModel \frontend\models\SearchEvent
 * @var $signupForm \frontend\models\SignupForm
 * @var $loginForm \common\models\LoginForm
 * @var $category_id integer
 */

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\datetime\DateTimePicker;
use yii\helpers\Html;

\frontend\assets\AppAsset::register($this);


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

    <script src="libs/modernizr/modernizr.js"></script>

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
    <?=$this->render('/site/_index_header') ?>
</header>
<!-- end-->
<section class="poisk">
    <div class="container">
        <div class="row">
            <div class="left-head col-sm-6">
                <div class="bread-crumbs">
                    <a href="<?=Url::to(['site/index'])?>">Главная</a>
                </div>
                <h3>Поиск по ленте событий</h3>
            </div>
            <div class="right-head col-sm-6">
            </div>
        </div>
    </div>
</section>

<!-- табы категорий -->

<div class="wrap-poisk-category">
    <?=$this->render('_poisk_cat', ['choosen_cat_id' =>$category_id]) ?>
</div>
<!-- start блок ленты событий-->
<section id="event" class="event-wrap">
    <div class="container">
        <!-- start фильтр по дате-->
            <div class="datetime_filter">
                <?php $form = ActiveForm::begin(['method' => 'get', 'options' => ['data-pjax' => true]]); ?>
                <h4>Фильтр событий по дате и времени:</h4>
                <?= $form->field($searchModel, 'dateFrom')->widget(DateTimePicker::classname(), [
                    'options' => ['placeholder' => 'Выберите дату и время начала интервала ...'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd hh:ii'
                    ]
                ])->label(false); ?>
                <?= $form->field($searchModel, 'dateTo')->widget(DateTimePicker::classname(), [
                    'options' => ['placeholder' => 'Выберите дату и время окончания интервала ...'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd hh:ii'
                    ]
                ])->label(false); ?>
                <?= Html::submitButton('Фильтровать', ['class' => 'btn btn-primary']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        <!-- конец формы-->
        <!-- start вывод событий-->
        <?=$this->render('_event_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]) ?>
        <?=$this->render('/layouts/paginator', [
            'paginator' => $dataProvider->pagination,
            'type' => 2
        ])?>
    </div>
</section>
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
<?=$this->render('/site/_form_city_choose')?>
<!-- форма регистрации-->
<?=$this->render('/site/_form_signup', [
    'signupForm' => $signupForm,
    'loginUrl' => $loginUrl,
])?>
<!-- форма входа-->
<?=$this->render('/site/_index_login', [
    'loginForm' => $loginForm,
    'loginUrl' => $loginUrl
])?>


<!-- всплывающая подсказка-->
<div class="hidden">
    <div id="head-help"class="head-help">
        <h3>Как работает TimePlace.me</h3>
        <div class="content">
            <p><span><b>TimePlace.me</b></span> <b>— новый ресурс для всех категорий пользователей интересующихся жизнью за пределами экранов компьютеров и мобильных устройств. </b></p>
            <h4>Для чего <span>TimePlace.me</span>?</h4>
            <p>Вся наша жизнь состоит из маленьких и больших событий, частных и общественных.</p>
            <p>При всем обилии информации, бывает сложно найти интересное и своевременное событие, найти занятие по душе, увлечься новым хобби, завести знакомства, организовать поход, спланировать свободное время в турпоездке, командировке. <span>TimePlace.me</span> создан для поиска актуальных событий прикрепленых по времени и месту и разбитых по категория для удобства поиска. Система поиска позволяет найти зарегестрированное событие в два-три клика. А лента событий покажет ближайшие по времени мероприятия, в месте, которое вас интересует. <span>TimePlace.me</span> поможет создать любое событие: от закрытой вип-вечеринки до благотворительного концерта. Быстро и легко, а главное бесплатно. Приехав в незнакомый город, вы всегда сможете найти увлечение по интересам, земляков в чужой стране, самые актуальные мероприятия и акции. Просто запланируйте или создайте событие, а <span>TimePlace.me</span> напомнит вам о нем.</p>
            <p>Своим событием вы можете сразу поделиться в популярных социальных сетях — VK, Facebook, Odnoklassniki, Twitter, Мой мир, Google+.</p>
            <p>В <span>TimePlace.me</span> заложена возможность создать свой круг друзей, общаться друг с другом, комментировать события.</p>
            <p>Цель проекта <span>TimePlace.me</span> — помогать людям информировать друг друга о происходящих актуальных событиях, знакомится с новыми людьми и проектами, а также продвигать свои события, привлекая новых людей, клиентов, единомышенников, партеров, друзей. Мы за мир во все мире!)</p>
            <p>C уважением и надеждой на обратную связь, команда создателей <span>TimePlace.me</span>.</p>
        </div>
    </div>
</div>

<!-- прелоадер-->
<div class="loader">
    <div class="loader_inner"></div>
</div>

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


</body>
<?php $this->endPage() ?>