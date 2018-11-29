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

$this->title = 'orderView';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">
    <div class="panel-body">
        <form action="" method="get" id="search-form" class="filter-form">
            <table width="100%" class="table table-bordered">
                <tbody>
                <tr >
                    <td width="10%" class="text-right">订单编号：</td>
                    <td width="10%"  class="text-left"><input type="text" class="form-control" name="order_no" id="order_no" value="<?php if(!empty($gets['order_no'])){ echo $gets['order_no']; } ?>"></td>
                    <td width="10%"  class="text-right">下单时间（起）：</td>
                    <td width="20%" class="text-left"><input type="date" class="form-control" name="order_begin" id="order_begin" value="<?php if(!empty($gets['order_begin'])){ echo $gets['order_begin']; } ?>"></td>
                    <td width="10%"  class="text-right">下单时间（止）：</td>
                    <td width="20%" class="text-left"><input type="date" class="form-control" name="order_end" id="order_end" value="<?php if(!empty($gets['order_end'])){ echo $gets['order_end']; } ?>"></td>
                </tr>
                <tr >
                    <td class="text-right">客户姓名：</td>
                    <td class="text-left"><input type="text" class="form-control" name="customer_name" id="customer_name" value="<?php if(!empty($gets['customer_name'])){ echo $gets['customer_name']; } ?>"></td>
                    <td class="text-right">客户电话：</td>
                    <td class="text-left"><input type="text" class="form-control" name="customer_phone" id="customer_phone" value="<?php if(!empty($gets['customer_phone'])){ echo $gets['customer_phone']; } ?>"></td>
                    <td class="text-right">设备拥有者：</td>
                    <td class="text-left"><input type="text" class="form-control" name="owner_name" id="owner_name" value="<?php if(!empty($gets['owner_name'])){ echo $gets['owner_name']; } ?>"></td>
                </tr>

                <tr class="search">
                    <td colspan="6"  class="text-center">
                        <button type="submit" class="btn btn-primary btncls" id="search"><i class="glyphicon glyphicon-search"></i> 查 询  </button>
                        <a class="btn btn-default btncls" href="">重&nbsp;&nbsp;&nbsp;&nbsp;置</a>
                        <a class="btn btn-success btncls" id="exportBtn" href="javascript:void(0)">导出订单报表</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>

<div class="panel panel-default">
    <div  class="panel-body">

        <?php if (!empty($orderList)) : ?>
            <table class="table table-striped table-bordered"  id="brand-table">
                <thead>
                <tr>
                    <th style="width:6%;">订单ID</th>
                    <th style="width:40%;">订单信息</th>
                    <th style="width:12%;">下单时间</th>
                    <th style="width:7%;">订单状态</th>
                    <th style="width:7%;">接单人员</th>
                    <th style="width:8%;">是否回访完毕</th>
                    <th style="width:20%;">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($orderList as $order) : ?>
                    <tr>
                        <td class="text-center align-middle hqy-row-select"><?=$order['id'] ?></td>
                        <td >
                            订单编号:<span style="font-weight: bold; color: #C40000; margin-left: 10px;"><?= $order['order_no'] ?></span><br/>
                            账户信息:<span style="font-weight: bold; margin-left: 10px;"><?= $order['nickname'] ?>(<?=$order['phone'] ?>)</span><br/>
                            联系信息:<span style="font-weight: bold; margin-left: 10px;"><?= $order['contact_name'] ?>(<?=$order['contact_phone'] ?>)</span><br/>
                            设备名称:<a style="margin-left: 10px;" href="<?=$order['device_info_url']; ?>"><?=$order['repair_device_name']; ?></a><br/>
                            设备拥有者:<span style="font-weight: bold; margin-left: 10px;"><?= $order['ownername'] ?></span><br/>
                            设备绑定人:<span style="font-weight: bold; margin-left: 10px;"><?= !empty($order['bind_engineer_name']) ? $order['bind_engineer_name'] : $order['bind_engineer_nick_name'] ?></span><br/>
                            上门地址:<span style="font-weight: bold; margin-left: 10px;"><?= $order['title'] ?> <?= $order['address_detail'] ?> (<?= $order['address'] ?>)</span><br/>
                            报修渠道:<span style="font-weight: bold; margin-left: 10px;"><?= $channels[$order['repair_channel']] ?></span>
                        </td>
                        <td >
                            <?=\Yii::$app->formatter->asTime($order['created_at'],"yyyy-MM-dd HH:mm:ss"); ?>
                        </td>
                        <td >
                            <?=$orderStatusList[$order['status']];?>
                            <?php if($order['is_admin_close']==1){
                                echo "(管理员关闭)";}
                            else if($order['is_user_close']==1){
                                echo "(用户处理关闭)";}
                            else if($order['is_engineer_close']==1){
                                echo "(工程师处理关闭)";}
                            else if($order['is_suspend'] != \common\business\SuspendOrder::SUSPEND_STATUS_OFF){
                                echo \common\business\SuspendOrder::suspendLabels($order['is_suspend']);}
                            ?><br/>
                        </td>
                        <td >
                            <?= $order['engineer_name'] ?>
                        </td>
                        <td >
                            <b style="color: #C40000;font-size: 13px;">
                                <?= (isset($order['interview_finished']) && $order['interview_finished'])==1 ? "是" : "否" ?>
                            </b>
                        </td>
                        <td >
                            <a class="btn btn-primary" style="margin: 4px;" href="">查看详情</a>
                            <?php if($order['status'] == 0): ?>
                                <a class="btn btn-success" style="margin: 4px;" href="">平台派单</a>
                            <?php endif; ?>
                            <?php if($order['apppeal_status'] >= 0 && is_numeric($order['apppeal_status'])): ?>
                                <a class="btn btn-warning" style="margin: 4px;" href="">查看质保</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p class="text-center">
                没有找到数据
            </p>
        <?php endif; ?>
    </div>



</div>