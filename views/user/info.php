<?php
/**
 * Created by PhpStorm.
 * User: Lixuan
 * Date: 2019/1/8
 * Time: 10：01
 */

use yii\helpers\Html;
use app\widgets\linkpage;
use app\assets\AppAsset;
use yii\helpers\Url;

$this->title = '用户详情';
$this->params['breadcrumbs'][] = '用户管理';
$this->params['breadcrumbs'][] = ['label' => '用户列表', 'url' => ['/user/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<style type="text/css">
    .head_img{
        width: 55px;
        height: 55px;
        margin:  0 auto;
    }
    .box{
        max-width: 800px;
        margin: 0 auto;
        padding: 0 20px;
    }
    .box-panel{
        width: 100%;
        max-height: 184px;
    }
</style>

<div class="box">
<div class="panel-heading">
    <h3>基本信息</h3>
</div>
<div class="panel box-panel">
    <form action="" method="get" id="search-form" class="filter-form">
            <table width="100%" class="col-sm-12 table none-bordered" style="font-size: 14px">
                <tbody>
                <tr >
                    <td width="20%" class="text-right">用户头像：</td>
                    <td width="30%"  class="text-left"><img src="<?=$avatar['avatarUrl'] ?>" class='head_img' ></td>
                    <td width="20%"  class="text-right">用户姓名：</td>
                    <td width="30%" class="text-left"><?=$info['stuname']?></td>
                </tr>
                <tr >
                    <td class="text-right">用户学号：</td>
                    <td class="text-left"><?=$info['stunumber']?></td>
                    <td class="text-right">用户状态:</td>
                    <td class="text-left" style="color: red"><?php if ($avatar['is_bind']){echo '认证成功';}else{echo "认证中";}?></td>
                </tr>
                <tr >
                    <td class="text-right">用户电话：</td>
                    <td class="text-left"><?=$avatar['phone']?></td>
                    <td class="text-right">用户身份证:</td>
                    <td class="text-left"><?=$info['idcard']?></td>
                </tr>
                <tr >
                    <td class="text-right">禁用状态：</td>
                    <td class="text-left">
                        <?php if($info['is_close'] == 'true'): ?>
                            <a class="btn btn-primary" style=" background-color: #b94a48; border: none" id="openAccount" >解禁</a>
                        <?php else: ?>
                            <a class="btn btn-primary" id="closeAccount">未禁用</a>
                        <?php endif; ?>
                    </td>
                    <td class="text-right">禁言状态：</td>
                    <td class="text-left">
                        <?php if($info['is_comment_close'] == 'true'): ?>
                            <a class="btn btn-primary" style=" background-color: #b94a48; border: none" id="openComment" >解禁</a>
                        <?php else: ?>
                            <a class="btn btn-primary" id="closeComment">未禁用</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr >
                    <td class="text-right">用户学生信息：</td>
                    <td class="text-left"><a id='openstu'>显示</a></td>
                </tr>
                </tbody>
            </table>
    </form>
</div>

<div class="panel-heading">
    <h3>学生个人信息</h3>
</div>
<div class="panel-body">
    <ul id="stuPersonTab" class="nav nav-tabs" style="margin-bottom: 20px">
        <li class="active"><a><h3 class="panel-title">课表信息</h3></a></li>
        <li><a><h3 class="panel-title">个人信息</h3></a></li>
        <input type="button" class="btn btn-primary" style="float: right; display: none;" id='updown' value=" 收 起 ">
    </ul>
    <div id='tab-con' style="display: none;">
        <div >
            <table class="table table-bordered" style="text-align: center;">
            <option>
                <select></select>
            </option>
            <option>
               <select></select>
            </option>

            <thead>
               <tr>
                  <td style="width: 5%">节</td>
                  <td style="width: 14%">一</td>
                  <td style="width: 14%">二</td>
                  <td style="width: 14%">三</td>
                  <td style="width: 14%">四</td>
                  <td style="width: 14%">五</td>
                  <td style="width: 14%">六</td>
                  <td style="width: 14%">日</td>
                </tr>
            </thead>
            <?php if($scgedular != null):?>
            <tbody>
                <?php foreach($scgedular as $key =>$item): ?>
                <tr>
                   <td><?=$key+1?></td>
                   <td><?=$item['monday']?></td>
                   <td><?=$item['tuesday']?></td>
                   <td><?=$item['wednesday']?></td>
                   <td><?=$item['thursday']?></td>
                   <td><?=$item['friday']?></td>
                   <td><?=$item['saturday']?></td>
                    <td><?=$item['sunday']?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        <?php endif;?>
        </table>
        </div>

        <div style="display: none;">
            
        </div>
    </div>     
</div>

<div class="panel-heading">
    <h3>订单信息</h3>
</div>
<div class="panel-body">
    <ul id="countryTab" class="nav nav-tabs" style="margin-bottom: 20px">
        <li <?php if($gets['status'] == 0){ echo "class=\"active\""; } ?> ><a href="<?=Url::toRoute(["/user/info", 'id' => $info['stunumber']])?>"><h3 class="panel-title">处理中订单</h3></a></li>
        <li <?php if($gets['status'] == 1){ echo "class=\"active\""; } ?>><a href="<?=Url::toRoute(["/user/info", 'id' => $info['stunumber'], 'order_type' => '1'])?>"><h3 class="panel-title">已完成订单</h3></a></li>
        <li <?php if($gets['status'] == 2){ echo "class=\"active\""; } ?>><a href="<?=Url::toRoute(["/user/info", 'id' => $info['stunumber'], 'order_type' => '2'])?>"><h3 class="panel-title">已取消订单</h3></a></li>
        <li <?php if($gets['status'] == 3){ echo "class=\"active\""; } ?>><a href="<?=Url::toRoute(["/user/info", 'id' => $info['stunumber'], 'order_type' => '3'])?>"><h3 class="panel-title">我发起的订单</h3></a></li>
    </ul>
    <table  class="table table-bordered" style="text-align: center; font-size: 12px">
        <thead>
            <tr >
                <td style="width:60px">编号</td>
                <td style="width:200px">订单编号</td>
                <td style="width:150px">用户名称</td>
                <td style="width:250px">订单类型</td>
                <td style="width:200px">状态</td>
            </tr>

        </thead>
        <?php if($orderlist != null):?>
        <tbody>
            <?php foreach($orderlist as $key => $item):?>
            <tr>
                <td><?=$key+1?></td>
                <td><a href="<?php echo Url::toRoute(['order/orderdetail', 'id' => $item['id']])?>"><?=$item['order_no']?></a></td>
                <td><?=$item['user_name']?></td>
                <td><?=$item['order_type']?></td>
                <td><?=$item['status_labal']?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <?php else: ?>
            <td colspan="6">暂无</td>
        <?php endif; ?>
    </table>
</div>

</div>

<script type="text/javascript">
   $(function() {
     $('#stuPersonTab li').click(function () {
            $(this).addClass('active').siblings().removeClass('active');
            var index=$(this).index();
            $("#tab-con div").eq(index).show().siblings().hide();
        })

     $('#openstu').click(function () {
           $('#tab-con').css({display:'block'});
           $('#updown').css({display:'block'});
     })
     $('#updown').click(function () {
        if($('#updown').val() == " 收 起 ") {
            $('#tab-con').css({display:'none'});
            $('#updown').val(' 显 示 ');
        } 
        else {
            $('#tab-con').css({display:'block'});
            $('#updown').val(' 收 起 ');
        }
     })

       $('#closeAccount').click(function () {
           var close_box = confirm('是否关闭该用户')
           if(close_box == true)
           {
               $.ajax({
                   url:'?r=user/closeaccount&id=<?=$info['stunumber'] ?>',
                   type: 'get',
                   dataType: 'json',
                   success: function (res) {
                       console.log(res)
                       if (!res.success) {
                           alert(res.error);
                       } else {
                           alert('禁用成功！');
                           setTimeout(function () {
                               window.location.href = '?r=user/info&id=<?=$info['stunumber'] ?>'
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
           var close_box = confirm('是否解禁该用户')
           if(close_box == true)
           {
               $.ajax({
                   url:'?r=user/openaccount&id=<?=$info['stunumber'] ?>',
                   type: 'get',
                   dataType: 'json',
                   success: function (res) {
                       console.log(res)
                       if (!res.success) {
                           alert(res.error);
                       } else {
                           alert('解禁成功！');
                           setTimeout(function () {
                               window.location.href = '?r=user/info&id=<?=$info['stunumber'] ?>'
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

       $('#openComment').click(function () {
           var close_box = confirm('是否解禁该用户')
           if(close_box == true)
           {
               $.ajax({
                   url:'?r=user/opencomment&id=<?=$info['stunumber'] ?>',
                   type: 'get',
                   dataType: 'json',
                   success: function (res) {
                       console.log(res)
                       if (!res.success) {
                           alert(res.error);
                       } else {
                           alert('解禁成功！');
                           setTimeout(function () {
                               window.location.href = '?r=user/info&id=<?=$info['stunumber'] ?>'
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

           $('#closeComment').click(function () {
               var close_box = confirm('是否禁言该用户')
               if(close_box == true)
               {
                   $.ajax({
                       url:'?r=user/closecomment&id=<?=$info['stunumber'] ?>',
                       type: 'get',
                       dataType: 'json',
                       success: function (res) {
                           console.log(res)
                           if (!res.success) {
                               alert(res.error);
                           } else {
                               alert('禁言成功！');
                               setTimeout(function () {
                                   window.location.href = '?r=user/info&id=<?=$info['stunumber'] ?>'
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