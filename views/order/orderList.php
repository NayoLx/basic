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

$this->title = '订单列表';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">
    <div class="panel-body">
        <form action="<?=Url::toRoute("/order/orderlist")?>" method="get" id="search-form" class="filter-form">
            <table width="100%" class="table table-bordered">
                <tbody>
                    <tr >
                        <td width="10%" class="text-right">订单编号：</td>
                        <td width="10%"  class="text-left"><input type="text" class="form-control" name="order_no" id="order_no" value="<?php if(!empty($gets['order_no'])){ echo $gets['order_no']; } ?>" ></td>
                        <td width="10%"  class="text-right">用户姓名：</td>
                        <td width="20%" class="text-left"><input type="text" class="form-control" name="user_name" id="user_name" value="<?php if(!empty($gets['user_name'])){ echo $gets['user_name']; } ?>" ></td>
                        <td width="10%"  class="text-right">用户学号：</td>
                        <td width="20%" class="text-left"><input type="text" class="form-control" name="user_id" id="user_id" value="<?php if(!empty($gets['user_id'])){ echo $gets['user_id']; } ?>" ></td>
                    </tr>
                    <tr >
                        <td class="text-right">用户电话：</td>
                        <td class="text-left"><input type="text" class="form-control" name="user_phone" id="user_phone" value="<?php if(!empty($gets['user_phone'])){ echo $gets['user_phone']; } ?>" ></td>
                        <td class="text-right">接单人姓名：</td>
                        <td class="text-left"><input type="text" class="form-control" name="staff_name" id="staff_name" value="<?php if(!empty($gets['staff_name'])){ echo $gets['staff_name']; } ?>" ></td>
                        <td class="text-right">接单人学号：</td>
                        <td class="text-left"><input type="text" class="form-control" name="staff_id" id="staff_id" value="<?php if(!empty($gets['staff_id'])){ echo $gets['staff_id']; } ?>" ></td>
                    </tr>

                    <tr class="search">
                        <td colspan="6"  class="text-center">
                            <a type="submit" class="btn btn-primary btncls" id="search"><i class="glyphicon glyphicon-search"></i> 查 询  </a>
<!--                             <a class="btn btn-primary btncls" href="--><?//=Url::toRoute("/order/orderlist)") ?><!--"><i class="glyphicon glyphicon-search"></i> 查 询  </a>-->
                            <!-- <a type="submit" class="btn btn-primary btncls" href="<?//=Url::toRoute(["order/orderlist", 'order_no' => $gets['order_no'], 'user_name' =>$gets['user_name'], 'user_id'=>$gets['user_id'], 'user_phone'=>$gets['user_phone'], 'staff_name'=>$gets['staff_name'],'staff_id'=>$gets['staff_id']])?>"><i class="glyphicon glyphicon-search"></i> 查 询  </a> -->
                            <a class="btn btn-default btncls" href="javascript:void(0)">重 置 </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>


    <div class="panel panel-default">
        <div  class="panel-body">

            <table class="table table-striped table-bordered"  id="brand-table">
                <thead>
                    <tr>
                        <th style="width:6%;">订单ID</th>
                        <th style="width:37%;">订单信息</th>
                        <th style="width:12%;">下单时间</th>
                        <th style="width:7%;">订单状态</th>
                        <th style="width:15%;">接单人员</th>
                        <th style="width:8%;">订单类型</th>
                        <th style="width:15%;">操作</th>
                    </tr>
                </thead>
                <?php if($orderlist != ''): ?>
                <tbody>
                <?php foreach($orderlist as $item): ?>
                <tr >
                    <td class="text-center align-middle hqy-row-select"><?= $item['id'] ?></td>
                    <td >
                        订单编号：<p style="color: #b94a48; font-weight: bold"><?= $item['order_no'] ?></p>
                        发起人： <?= $item['user_name'] ?><br/>
                        学号： <?= $item['user_stunum'] ?><br/>
                        所需性别： <?= $item['sex'] ?><br/>
                        所需时间：<?= $item['express_detail_starttime'] ?> - <?= $item['express_detail_endtime'] ?><br/>
                        留言： <?= $item['express_detail_text'] ?><br/>
                    </td>
                    <td >
                        <?= $item['push_time'] ?>
                    </td>
                    <td >
                        <?= $item['status_labal'] ?>
                    </td>
                      <?php if ($item['staff_stunum'] != null && $item['staff_stunum'] != '' && $item['staff_stunum'] != 0): ?>
                       <td>
                           <?= $item['staff_name'] ?><br/>
                           id：<?= $item['staff_stunum'] ?>
                       </td>
                    <?php else: ?>
                       <td>
                          暂无人接单
                        </td>
                    <?php endif;?>
                    <td >
                        <?= $item['order_type'] ?>
                    </td>
                    <td >
                        <a class="btn btn-primary" style="margin: 4px;" href="<?php echo Url::toRoute(['order/orderdetail', 'id' => $item['id']])?>">查看详情</a>
                        <?php if ($item['status'] == 1): ?>
                          <a class="btn btn-success" style="margin: 4px;" href="<?php echo Url::toRoute(['order/orderpai', 'id' => $item['id']])?>">平台派单</a>
                        <?php endif;?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            <?php else: ?>
            <p class="text-center">
               没有找到数据
            </p>
          <?php endif; ?>
        </table>

    </div>

</div>

<script type="text/javascript">
    $(function(){
            $('#search').bind("click", function () {
                var export_url = "?r=order/orderlist&order_no="+$("#order_no").val()+"&user_name="+$("#user_name").val() + "&user_id=" + $("#user_id").val() + "&user_phone=" + $("#user_phone").val() + "&staff_name=" + $("#staff_name").val() + "&staff_id=" + $("#staff_id").val();
                window.location.href = export_url;
            })
        })
</script>
