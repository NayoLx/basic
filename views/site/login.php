<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>

<style>

    .copyright_inner p {
        line-height: 23px;
        color: #fff;
    }

    .copyright_inner a {
        line-height: 23px;
        color: #fff;
    }

    body{
        padding-top:40px;
        padding-bottom:40px;
        background-color:#eee
    }

    .form-signin{
        max-width:330px;
        padding:15px;
        margin:0 auto
    }

    .form-signin .checkbox,.form-signin .form-signin-heading{
        margin-bottom:10px
    }

    .form-signin .checkbox{
        font-weight:400
    }

    .form-signin .form-control{
        position:relative;
        height:auto;
        -webkit-box-sizing:border-box;
        -moz-box-sizing:border-box;
        box-sizing:border-box;
        padding:10px;
        font-size:16px
    }

    .form-signin .form-control:focus{
        z-index:2
    }

    .form-signin input[type=text]{
        margin-bottom:10px;
        border-bottom-right-radius:0;
        border-bottom-left-radius:0;
    }

    .form-signin input[type=password]{
        margin-bottom:10px;
        border-top-left-radius:0;
        border-top-right-radius:0;
    }
</style>

<div class="container">
    <form class="form-signin" method="post" action="">
        <h2 class="form-signin-heading">账户登录</h2>
        <label for="name" class="sr-only">账号</label>
        <input type="text" name="name" class="form-control" placeholder=" 账号" required autofocus>
        <label for="password" class="sr-only">密码</label>
        <input type="password" name="password" class="form-control" placeholder="密码" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">登录</button>
    </form>

</div>
