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
                <input type="text" id="username" class="form-control required" placeholder="用户名" value="<?=$user['username']?>">
            </div>

            <div class="col-md-12" style="padding-top:15px;"></div>

            <div class="col-md-3 text-right" style="padding:5px 0px 0px 0px;">
                <span class="text-danger"><span class="	glyphicon glyphicon-asterisk"></span></span>
                <b>姓名</b>
            </div>
            <div class="col-md-6">
                <input type="text" id="realname" class="form-control required" placeholder="姓名" value="<?=$user['name']?>">
            </div>

            <div class="col-md-12" style="padding-top:15px;"></div>

            <div class="col-md-3 text-right" style="padding:5px 0px 0px 0px;">
                <span class="text-danger"><span class="glyphicon glyphicon-asterisk"></span></span>
                <b>邮箱</b>
            </div>
            <div class="col-md-6">
                <input type="text" id="email" class="form-control required email" placeholder="邮箱" value="<?=$user['e-mail']?>">
            </div>


            <div class="col-md-12" style="padding-top:15px;"></div>

            <div class="col-md-3 text-right" style="padding:5px 0px 0px 0px;">
                <span class="text-danger"><span class="	glyphicon glyphicon-asterisk"></span></span>
                <b>手机</b>
            </div>
            <div class="col-md-6">
                <input type="text" id="mobile" class="form-control required isMobile" placeholder="手机号码" value="<?=$user['phone']?>">
            </div>

            <div class="col-md-12" style="padding-top:15px;"></div>

            <div class="col-md-3 text-right" style="padding:5px 0px 0px 0px;">
                <span class="text-danger"><span class="	glyphicon glyphicon-asterisk"></span></span>
                <b>禁用状态</b>
            </div>
            <div class="col-md-6">
                <?php if($user['is_close'] == 'true'): ?>
                    <a class="btn btn-primary" style=" background-color: #b94a48; border: none" id="openAccount" >解禁</a>
                <?php else: ?>
                    <a class="btn btn-primary" style=" background-color: #b94a48; border: none" id="closeAccount" >禁用</a>
                <?php endif; ?>
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
            var realname = $("#realname").val();
            var email = $("#email").val();
            var mobile = $("#mobile").val();
            var is_close = $('#is_close').val();

            $.ajax({
                type: 'GET',
                url: export_url,
                dataType: 'json',
                data: {
                    username: username,
                    realname: realname,
                    email: email,
                    mobile: mobile,
                    is_close: is_close,
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


        $('#closeAccount').click(function () {
            var close_box = confirm('是否关闭该账号')
            if(close_box == true)
            {
                $.ajax({
                    url:'?r=system/closeaccount&id=<?=$user['id'] ?>',
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        console.log(res)
                        if (!res.success) {
                            alert(res.error);
                        } else {
                            alert('禁用成功！');
                            setTimeout(function () {
                                window.location.href = '?r=system/adminsetting&id=<?=$user['id'] ?>'
                            }, 500);
                        }
                    }
                })
            }
            else if(close_box == false)
            {
                console.log('false');
            }
        })

        $('#openAccount').click(function () {
            var close_box = confirm('是否解禁该账号')
            if(close_box == true)
            {
                $.ajax({
                    url:'?r=system/openaccount&id=<?=$user['id'] ?>',
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        console.log(res)
                        if (!res.success) {
                            alert(res.error);
                        } else {
                            alert('解禁成功！');
                            setTimeout(function () {
                                window.location.href = '?r=system/adminsetting&id=<?=$user['id'] ?>'
                            }, 500);
                        }
                    }
                })
            }
            else if(close_box == false)
            {
                console.log('false');
            }
        })
    })
</script>