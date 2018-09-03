<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\assets\AppAsset;

$this->title = 'personal';
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="content">

</div>

<script type="text/javascript">
    $(function () {
        $.ajax({
            url: '?r=my/peosonal',
            dataType: 'json',
            success : function(data) {   //如何发送成功
                var html = "";
                $("#content").html(html);
            },
        })
    })
</script>