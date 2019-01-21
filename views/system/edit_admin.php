<?php
/**
 * Created by PhpStorm.
 * User: Lixuan
 * Date: 2019/1/18
 * Time: 18:24
 */


use yii\helpers\Url;
$this->title =  "新建账号";
$this->params['breadcrumbs'][] = '账号修改';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="panel panel-default">
    <div class="panel-heading text-center">
        <h4>账号修改</h4>

    </div>
    <div class="panel-body">
        <form action="" id="accountForm" method="post" >
            <div class="col-md-3 text-right" style="padding:5px 0px 0px 0px;">
                <span class="text-danger"><span class="	glyphicon glyphicon-asterisk"></span></span>
                <b>用户名</b>
            </div>
            <div class="col-md-6">
                <input type="text" id="username" class="form-control required" placeholder="用户名" value="">
            </div>

            <div class="col-md-12" style="padding-top:15px;"></div>

            <div class="col-md-3 text-right" style="padding:5px 0px 0px 0px;">
                <span class="text-danger"><span class="	glyphicon glyphicon-asterisk"></span></span>
                <b>姓名</b>
            </div>
            <div class="col-md-6">
                <input type="text" id="realname" class="form-control required" placeholder="姓名" value="">
            </div>

            <div class="col-md-12" style="padding-top:15px;"></div>

            <div class="col-md-3 text-right" style="padding:5px 0px 0px 0px;">
                <span class="text-danger"><span class="glyphicon glyphicon-asterisk"></span></span>
                <b>邮箱</b>
            </div>
            <div class="col-md-6">
                <input type="text" id="email" class="form-control required email" placeholder="邮箱" value="">
            </div>


            <div class="col-md-12" style="padding-top:15px;"></div>

            <div class="col-md-3 text-right" style="padding:5px 0px 0px 0px;">
                <span class="text-danger"><span class="	glyphicon glyphicon-asterisk"></span></span>
                <b>手机</b>
            </div>
            <div class="col-md-6">
                <input type="text" id="mobile" class="form-control required isMobile" placeholder="手机号码" value="">
            </div>

            <div class="col-md-12" style="padding-top:15px;"></div>



            <div class="col-md-12" style="padding-top:15px;"></div>

            <div class="col-md-3 text-right" >
                &nbsp;
            </div>
            <div class="col-md-6">
                <a type="submit" class="btn btn-primary" id="save">修&nbsp;&nbsp;改</a>
            </div>

        </form>
    </div>
</div>

<script type="text/javascript">
    $(function(){
        $('#save').bind("click", function () {
            var export_url = "?r=system/submitedit";
            var username = $("#username").val();
            var password = $("#password").val();
            var password_confirm = $("#password_confirm").val();
            var realname = $("#realname").val();
            var email = $("#email").val();
            var mobile = $("#mobile").val();

            $.ajax({
                type: 'GET',
                url: export_url,
                dataType: 'json',
                data: {
                    username: username,
                    password: password,
                    password_confirm: password_confirm,
                    realname: realname,
                    email: email,
                    mobile: mobile,
                },
                success:function(res) {
                    console.log(res);
                    if (!res.success) {
                        alert(res.error);
                    } else {
                        alert('修改成功！');
                        setTimeout(function () {
                            window.location.href = '?r=system/index'
                        }, 500);
                    }
                },
                error:function (res) {
                    console.log(res.responseText);
                }
            })
        })
    })
</script>