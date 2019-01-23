<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1><?=Yii::$app->session['username']?>欢迎!</h1>
    </div>

</div>
