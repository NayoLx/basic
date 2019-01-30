<?php
/**
 * Created by PhpStorm.
 * User: Lixuan
 * Date: 2018/11/28
 * Time: 14:31
 */

use yii\helpers\Html;
use app\widgets\linkpage;
use app\assets\AppAsset;
use yii\helpers\Url;

$this->title = '订单详情';
$this->params['breadcrumbs'][] = '订单管理';
$this->params['breadcrumbs'][] = ['label' => '订单列表', 'url' => ['/order/orderlist']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">
    <form action="" method="get" id="search-form" class="filter-form">
            <table width="100%" class="col-sm-12 table table-bordered">
                <tbody>
                <tr>
                    <td colspan="6" class="bg-info">订单信息</td>
                </tr>
                <tr >
                    <td width="10%" class="text-right">订单编号：</td>
                    <td width="10%"  class="text-left"><?=$data['order_no'] ?></td>
                    <td width="10%"  class="text-right">下单时间：</td>
                    <td width="20%" class="text-left"><?=$data['push_time'] ?></td>
                    <td width="10%"  class="text-right">结束时间：</td>
                    <td width="20%" class="text-left"></td>
                </tr>
                <tr >
                    <td class="text-right">用户姓名：</td>
                    <td class="text-left"><?=$data['user_name']?></td>
                    <td class="text-right">用户电话：</td>
                    <td class="text-left"></td>
                    <td class="text-right">用户学号：</td>
                    <td class="text-left"><?=$data['user_stunum'] ?></td>
                </tr>
                <tr >
                    <td class="text-right">接单人姓名：</td>
                    <td class="text-left"><?=$data['staff_name']?></td>
                    <td class="text-right">接单人电话：</td>
                    <td class="text-left"><?=$staff_phone['phone']?></td>
                    <td class="text-right">接单人学号：</td>
                    <td class="text-left"><?=$data['staff_stunum'] ?></td>
                </tr>
                <tr>
                    <td class="text-right">订单类型：</td>
                    <td class="text-left"><?=$data['order_type'] ?></td>
                    <td class="text-right">订单状态：</td>
                    <td class="text-left"><?=$data['status_labal'] ?></td>
                    <td class="text-right">所需性别：</td>
                    <td class="text-left"><?=$data['sex'] ?></td>
                </tr>
                <tr >
                    <td class="text-right">留言：</td>
                    <td class="text-left" colspan="6"><?=$data['express_detail_text'] ?></td>
                </tr>
                <tr class="search">
                    <td colspan="6"  class="text-center">
                        <?php if ($data['status'] != 6 && $data['status'] != 5): ?>
                            <a class="btn btn-primary btncls" style=" float: right;" id="close_order">关闭订单</a>
                        <?php endif;?>
                        <?php if ($data['status'] == 1): ?>
                            <a class="btn btn-primary btncls" style="background-color: #62C790; float: right; margin-right: 10px" <?=Url::toRoute(['order/orderpai', 'id' => $data['id']])?>>平台派单</a>
                        <?php endif;?>
                        <?php if ($data['status'] != 1 && $data['status'] != 2 && $data['status'] != 3): ?>
                            <a class="btn btn-primary btncls" style=" float: right" id="reopen">重新打开订单</a>
                        <?php endif;?>
                    </td>
                </tr>
                </tbody>
            </table>
    </form>
</div>

<div class="panel-body form-inline">
    <table class="table" style="border-top:none;">
        <tr>
            <td class="col-sm-6">
                <table class="table table-bordered">
                    <tr>
                        <td class="bg-info">后台备注</td>
                    </tr>
                    <tr>
                        <td width="100%"><textarea class="form-control" style="width:100%;" rows="3" id="remark" name="remark"></textarea></td>
                    </tr>
                    <tr class="text-center">
                        <td>
                            <button type="button" class="btn btn-primary ladda-button" data-style="slide-up" id="save">提交备注</button>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="col-sm-6">
                <table class="table table-bordered">
                    <tr>
                        <td colspan="3" class="bg-info">操作日志</td>
                    </tr>
                    <tr>
                        <th width="150" >操作时间</th>
                        <th width="130" >操作人员</th>
                        <th >操作内容</th>
                    </tr>
                    <?php if($order_log):?>
                        <?php foreach($order_log as $key => $item):?>
                        <tr>
                            <td><?=$item['log_time']?></td>
                            <td><?php if($item['log_type'] == 0){echo '客户';}
                            elseif($item['log_type'] == 1) {echo "接单人";}elseif($item['log_type'] == 2) {echo "后台人员";} else { echo "系统";}?> <?=$item['log_master']?></td>
                            <td><?=$item['log_message']?></td>
                        </tr>
                    <?php endforeach;?>
                    <?php else:?>
                        <tr>
                            <td colspan="3" style="text-align: center;">暂无日志信息</td>
                        </tr>
                    <?php endif;?>
                </table>
            </td>
        </tr>
    </table>
</div>



<script type="text/javascript">
    $(function () {
        $('#close_order').click(function () {
            var close_box = confirm('是否关闭该订单')
            if(close_box == true)
            {
                $.ajax({
                    url:'?r=order/htcloseorder&id=<?=$data['id'] ?>&orderid=<?=$data['order_no'] ?>',
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        if(res.success){
                            var url = '?r=order/orderdetail&id=<?=$data['id'] ?>';
                            window.location.href = url;
                        }
                    }
                })
            }
            else if(close_box == false)
            {
                console.log('false');
            }
        })

        $('#reopen').click(function () {
            var close_box = confirm('是否重新打开该订单')
            if(close_box == true)
            {
                $.ajax({
                    url:'?r=order/reopenorder&id=<?=$data['id'] ?>&orderid=<?=$data['order_no'] ?>',
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        if(res.success){
                            var url = '?r=order/orderdetail&id=<?=$data['id'] ?>';
                            window.location.href = url;
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