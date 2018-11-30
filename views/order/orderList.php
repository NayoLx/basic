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

$this->title = '订单列表';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">
    <div class="panel-body">
        <form action="" method="get" id="search-form" class="filter-form">
            <table width="100%" class="table table-bordered">
                <tbody>
                    <tr >
                        <td width="10%" class="text-right">订单编号：</td>
                        <td width="10%"  class="text-left"><input type="text" class="form-control" name="order_no" id="order_no" ></td>
                        <td width="10%"  class="text-right">用户姓名：</td>
                        <td width="20%" class="text-left"><input type="text" class="form-control" name="order_begin" id="user_name"></td>
                        <td width="10%"  class="text-right">用户学号：</td>
                        <td width="20%" class="text-left"><input type="text" class="form-control" name="order_end" id="user_id" ></td>
                    </tr>
                    <tr >
                        <td class="text-right">用户电话：</td>
                        <td class="text-left"><input type="text" class="form-control" name="customer_phone" id="user_phone" ></td>
                        <td class="text-right">接单人姓名：</td>
                        <td class="text-left"><input type="text" class="form-control" name="customer_name" id="staff_name"></td>
                        <td class="text-right">接单人学号：</td>
                        <td class="text-left"><input type="text" class="form-control" name="customer_name" id="staff_id"></td>
                    </tr>

                    <tr class="search">
                        <td colspan="6"  class="text-center">
                            <a type="submit" class="btn btn-primary btncls" id="search">查 询  </a>
                            <a class="btn btn-default btncls" href="">重 置 </a>
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
                        <a class="btn btn-primary" style="margin: 4px;" id = "todetail" value = "{{id}}" href = "index.php?r=order/orderdetail">查看详情</a>
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

       /**访问api层专用*/
     function templateMethod(data) {
        var template = $('#template').html();
        var compiled = Template7.compile(template);
        var htmlStr = compiled(data);
        $('#content').html(htmlStr);
     }

     /**全局显示变量*/
     var data = [];

     $.get('?r=order/orderall',
         function (data, status) {
             data = data;
             templateMethod(data);
         },'json'
     );

     $('#search').click(function () {
         var order_no = $('#order_no').val();

         $.post('?r=order/orderfuzzysearch', {
             order_val: order_no
             },
             function (data) {
             if (data) {
                 data = data;
                 templateMethod(data);
             }
             },'json'
         )
     });
   })

    $(function () {

        $('#todetail').click(function () {
            var id = $('#todetail').val();
            session_start();
            $_SESSION['id'] = id;
        })
    })
</script>

<div id="content">

</div>