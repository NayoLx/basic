<?php
/**
 * Created by PhpStorm.
 * User: Lixuan
 * Date: 2018/11/30
 * Time: 11:30
 */

use yii\helpers\Html;
use app\widgets\linkpage;
use app\assets\AppAsset;

$this->title = '用户列表';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">
    <div class="panel-body">
        <form action="" method="get" id="search-form" class="filter-form">
            <table width="100%" class="table table-bordered">
                <tbody>
                <tr >
                    <td width="10%"  class="text-right">用户姓名：</td>
                    <td width="20%" class="text-left"><input type="text" class="form-control" name="order_begin" id="user_name"></td>
                    <td width="10%"  class="text-right">用户学号：</td>
                    <td width="20%" class="text-left"><input type="text" class="form-control" name="order_end" id="user_id" ></td>
                    <td class="text-right">用户电话：</td>
                    <td class="text-left"><input type="text" class="form-control" name="customer_phone" id="user_phone" ></td>
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
                    <th style="width:10%;">学生id</th>
                    <th style="width:10%">头像</th>
                    <th style="width:10%;">学号</th>
                    <th style="width:10%;">姓名</th>
                    <th style="width:15%;">专业</th>
                    <th style="width:15%;">学校邮箱</th>
                    <th style="width:30%;">操作</th>
                </tr>
                </thead>
                {{#each userlist}}
                <tr>
                    <td class="text-center align-middle hqy-row-select">{{@index}}</td>
                    <td >

                    </td>
                    <td >
                        {{stunumber}}
                    </td>
                    <td >
                        {{stuname}}
                    </td>
                    <td>
                        {{major}}
                    </td>
                    <td >
                        {{schoolemail}}
                    </td>
                    <td >
                        <a class="btn btn-primary" style="margin: 4px;" id = "todetail" value = "{{id}}" href = "">查看详情</a>

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

        $.get('?r=user/userlist',
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


</script>

<div id="content">

</div>