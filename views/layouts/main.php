<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title>后台管理系统</title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => '小程序后台管理',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],//显示在右边
        'items' => [
            Yii::$app->session['be_login'] != 1 ? (
            ['label'=>'登录','url'=>['/user/login']]
            ) : '',
            Yii::$app->session['be_login'] == 1 ? (
               ['label' => Yii::$app->session['username'] . ',欢迎回来']
            ) : '',
            Yii::$app->session['be_login'] == 1 ? (
               ['label' => '退出', 'url'=>['/user/logout']]
            ) : '',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            Yii::$app->session['be_login'] == 1 ? ['label' => '首页', 'url' => ['/site/index']]: '',
            Yii::$app->session['be_login'] == 1 ? (['label' => '订单列表', 'url' => ['/order/orderlist']]) : '',
            Yii::$app->session['be_login'] == 1 ? ['label' => '用户列表', 'url' => ['/user/index']] : '',
            Yii::$app->session['be_login'] == 1 ? ['label' => '数据分析', 'url' => ['/data/index']] : '',
            Yii::$app->session['be_login'] == 1 ? ['label' => '角色分派', 'url' => ['/system/index']] : '',
            Yii::$app->session['be_login'] == 1 ? (
                    ['label' => '系统配置', 'url' => ['/setting/curlsetting']]
            ) : '',
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?=$content ?>
    </div>
</div>

<!--<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>-->

<!--<?php $this->endBody() ?>-->

</body>
</html>
<!--<?php $this->endPage() ?>-->

<script type="text/javascript">

</script>