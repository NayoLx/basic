<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\assets\AppAsset;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<?=Html::jsFile('@web/js/public.js')?>

<div style="margin: 0 auto; width: 400px; top:10px">
    学号：<input id="username" type="text"/><br>
    密码：<input id="password" type="password"/><br>
    <div style="margin: 0 auto; width: 260px; padding-top: 10px;">
        <button  class="submit" id="loginbtn" >查询</button>
    </div>
</div>

<script type="text/javascript">

   $(function() {
       $('#loginbtn').click(function () {
           var user = $("#username").val();
           var pass = $("#password").val();
           Login(user, pass);
       });
   });

    // function tocheck() {
    //     var user = $("#username").val();
    //     var pass = $("#password").val();
    //     Login(user, pass);
    // }
</script>

