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

$this->title = 'orderList';
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

<script id="template" type="text/template7">
    <div class="panel panel-default">
        <div  class="panel-body">

            <table class="table table-striped table-bordered"  id="brand-table">
                <thead>
                    <tr>
                        <th style="width:6%;">订单ID</th>
                        <th style="width:40%;">订单信息</th>
                        <th style="width:12%;">下单时间</th>
                        <th style="width:7%;">订单状态</th>
                        <th style="width:7%;">接单人员</th>
                        <th style="width:8%;">订单类型</th>
                        <th style="width:20%;">操作</th>
                    </tr>
                </thead>
                {{#each orderlist}}
                <tr>
                    <td class="text-center align-middle hqy-row-select">{{id}}</td>
                    <td >
                        订单编号： {{order_no}}<br/>
                        发起人： {{user_name}}<br/>
                        学号： {{user_stunum}}<br/>
                        所需性别： {{sex}}<br/>
                        所需时间： {{express_detail_starttime}} - {{express_detail_endtime}}<br/>
                        留言： {{express_detail_text}}<br/>
                    </td>
                    <td >
                        {{push_time}}
                    </td>
                    <td >
                        {{status_labal}}
                    </td>
                    {{#if staff_name}}
                       <td>
                           {{staff_name}}<br/>
                           id：{{staff_stunum}}
                       </td>
                    {{else}}
                       <td>
                          暂无人接单
                       </td>
                    {{/if}}
                    <td >
                        {{order_type}}
                    </td>
                    <td >
                        <a class="btn btn-primary" style="margin: 4px;" >查看详情</a>
                        {{#js_if "this.status == 1" }}
                           <a class="btn btn-success" style="margin: 4px;" >平台派单</a>
                        {{/js_if}}
                    </td>
                </tr>
                {{/each}}
            </tbody>
        </table>
        <p class="text-center">
            没有找到数据
        </p>
    </div>

</div>
</script>

<script type="text/javascript">
   $(function () {
     
     function templateMethod(data) {
        var template = $('#template').html();
        var compiled = Template7.compile(template);
        var htmlStr = compiled(data);
        $('#content').html(htmlStr);
     }

     var data = [];
     $.get('?r=order/orderall',
         function (data, status) {
             data = data;
             templateMethod(data);
         },'json'
     );
   });
</script>

<div id="content">

</div>