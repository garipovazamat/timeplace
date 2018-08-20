<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\OldAppAsset;
use common\widgets\Alert;
use yii\helpers\Url;
use common\models\Message;
use common\models\Category;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use common\models\City;
use common\models\Country;

if(!Yii::$app->session->has('id_city') && !Yii::$app->user->isGuest){
    if(isset(Yii::$app->user->identity->id_city))
        Yii::$app->session->set('id_city', Yii::$app->user->identity->id_city);
}
OldAppAsset::register($this);
//$this->registerJsFile('https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js', ['position' => \yii\web\View::POS_HEAD]);
//$this->registerJsFile('chosen_v1.4.2/chosen.jquery.js', ['position' => \yii\web\View::POS_HEAD]);

if(!Yii::$app->user->isGuest)
    Yii::$app->user->identity->setLastVisit();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">
    <?php
    if((Url::toRoute('') != Url::toRoute('site/index'))) {
        NavBar::begin([
            'brandLabel' => Html::img('/images/logo1.png'),
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        $menuItems = [
            ['label' => 'Главная', 'url' => ['site/index']],
            //['label' => 'About', 'url' => ['/site/about']],
            // ['label' => 'Контакты', 'url' => ['/site/contact']],
        ];
        if(Yii::$app->session->has('id_city')){
            $city = City::getSessionCity();
            $label = $city->name;
        } else {
            $label = 'Выбрать город';
        };
        $menuItems[] =
            ['label' => $label, 'url' => ['#'], 'linkOptions' => [
                'data-toggle' => 'modal',
                'data-target' => '#citychoose',
                'id' => 'choose_city_link'
            ]];
        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => 'Вход', 'url' => ['/site/login']];
        } else {
            $user = Yii::$app->user->identity;
            $invite_count = $user->getMyInvitesCount();
            if($invite_count != 0)
                $invite_count = "($invite_count)";
            else $invite_count = '';

            $new_mess_count = Message::find()
                ->where(['receiver' => Yii::$app->user->id, 'reading' => 0])
                ->count();
            $menuItems[] = [
                'label' => "Сообщения ($new_mess_count)",
                'url' => ['messages/show']
            ];
            if($invite_count>0)
                $menuItems[] = [
                    'label' => "Приглашения $invite_count",
                    'url' => ['user/invites']
                ];
            $menuItems[] = [
                'label' => 'Мои события',
                'url' => ['#'],
                'linkOptions' => [
                    'data-toggle' => 'modal',
                    'data-target' => '#my_events',
                    'id' => 'my_events_link'
                ]
            ];
            $menuItems[] = [
                'label' => 'Моя страница',
                'url' => ['user/index', 'id' => Yii::$app->user->id]
            ];
            $menuItems[] = [
                'label' => 'Выйти',
                'url' => ['/site/logout'],
                'linkOptions' => ['data-method' => 'post'],
            ];
        }
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
        NavBar::end();
    }
    ?>
    <div class="container" <?=(Url::toRoute('') == Url::toRoute('site/index'))?"style = 'padding: 0px 0px 0px; width:100%; padding-bottom: 60px; min-height: 100%;'":''?>>
        <?//=(Url::toRoute('') != Url::toRoute('site/index')) ? '<div class="layout_cloud">' . Category::getCloud() . '</div>' : '' ?>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
<div class="row">
    <div class="col-md-10" style="padding-top: 15px; min-height: 80vh; padding-bottom: 35px;">
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; TimePlace.me <?= date('Y') ?></p>

        <p class="pull-right">
            <?=Html::a('Как работает TimePlace.me', ['site/about'], ['class' => 'about_style']) ?>
        </p>
    </div>

</footer>
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
                        webvisor:true
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
    <noscript><div><img src="https://mc.yandex.ru/watch/34738740" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!— /Yandex.Metrika counter —>

<?php $this->endBody() ?>
<?
Modal::begin([
'header' => 'Выбор города',
'options' => [
'id' => 'citychoose',
]
]);
Url::remember();
if(Yii::$app->session->has('id_city'))
    $city = City::findOne(['id_city' => Yii::$app->session->get('id_city')]);
else $city = new City();
echo '<div class="choose_city_module">';
    $form = ActiveForm::begin([
    'action' => Url::to(['site/setcity']),
    'method' => 'post'
    ]);
    echo Html::dropDownList('country', null, Country::countryList(), ['class' => 'form-control',
            'onchange'=>'
                $.post( "'.Url::to(['site/selectcity']).'&id_country="+$(this).val(), function(data) {
                  $( "select#city_main" ).html(data);
                  $("select#city_main").trigger("chosen:updated");
                });
            '
    ]);
    echo $form->field($city, 'id_city')->dropDownList(City::cityList(), ['id' => 'city_main']);
    //echo '<div class="choose_city_button">';
        echo Html::submitButton('Выбрать', ['class' => 'btn btn-primary', 'style' => 'background: #337ab7; margin: 0 auto']);
        //echo '</div>';
    ActiveForm::end();
    echo '</div>';
Modal::end();

if(!Yii::$app->user->isGuest) {
    Modal::begin([
        'header' => 'Мои события',
        'options' => [
            'id' => 'my_events',
        ]
    ]);
    echo $this->render('/user/myevents');
    Modal::end();
}
?>
</body>
</html>
<?php $this->endPage() ?>