<?php
/**
 * Created by PhpStorm.
 * User: Lixuan
 * Date: 2019/1/22
 * Time: 11:03
 */
use yii\helpers\Url;
use app\ht\widgets\LinkPager;
use common\helpers\ImageManager;

$this->title = '手动分配订单';
$this->params['breadcrumbs'][] = '订单管理';
$this->params['breadcrumbs'][] = ['label' => '订单列表', 'url' => ['/order/orderlist']];
$this->params['breadcrumbs'][] =  $this->title;

?>

<div class="panel panel-default">
    <div class="panel-body">
        <table class="table table-bordered">
            <tr>
                <td colspan="4" class="bg-info">申请信息</td>
            </tr>
            <tr>
                <th class="col-sm-2">订单号</th>
                <td class="col-sm-4"></td>
                <th class="col-sm-2">订单状态</th>
                <td class="col-sm-4"></td>
            </tr>
            <tr>
                <th>报修设备</th>
                <td></td>
                <th>报修时间</th>
                <td></td>
            </tr>
            <tr>
                <th>帐户信息</th>
                <td></td>
                <th>公司名称</th>
                <td>

                </td>
            </tr>
            <tr>
                <th>上门地址</th>
                <td></td>
                <th>工程师</th>
                <td>
                    <input type="hidden" value="0" id="engineer_id" />
                    <input class="form-control" id="engineer" name="engineer" placeholder="请录入手机或名称" autocomplete="off" />

                </td>
            </tr>
        </table>
    </div>

    <div class="panel-body">
        <table class="table table-bordered">
            <tr>
                <td colspan="4" class="bg-info">客户故障描述</td>
            </tr>
            <tr>
                <td colspan="4">

                </td>
            </tr>
            <tr >

                    <style>
                        #img_alert {
                            width: 100%;
                            height: 100%;
                            display: none;
                            position: absolute;
                            top: 0px;
                            left: 0px;
                            background-color: rgba(0, 0, 0, 0.42);
                        }

                        #img_alert .img_content {
                            width: 604px;
                            height: auto;
                            position: relative;
                            left: 50%;
                            margin-left: -200px;
                            opacity: 0;
                            top: 50%;
                            border: 2px solid #ffffff;
                        }

                        #img_alert .img_content .closed {
                            width: 25px;
                            height: 25px;
                            background-color: #636363;
                            display: inline-block;
                            position: absolute;
                            top: -13px;
                            right: -10px;
                            text-align: center;
                            border-radius: 100%;
                            color: #fff;
                            cursor: pointer;
                        }

                        #img_alert .img_content img {
                            width: 600px;
                            display: inherit;
                            height: auto;
                        }
                    </style>


                    <div id="img_alert" style="z-index: 999;">
                        <div class="img_content">
                            <span class="closed">x</span>
                            <img src="" />
                        </div>
                    </div>

        </table>
    </div>

    <table class="table" style="border-top:none;">
        <tr>
            <td class="col-sm-12">
                <table class="table table-bordered">
                    <tr>
                        <td class="bg-info">后台备注</td>
                    </tr>
                    <tr>
                        <td width="100%"><textarea class="form-control" style="width:100%;" rows="3" id="remark" name="remark"></textarea></td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

    <div class="panel-footer text-center">
        <input type="hidden" value="" name="order_id" id="order_id" >
        <button type="button" class="btn btn-primary" id="save">提&nbsp;&nbsp;交</button>&nbsp;&nbsp;&nbsp;&nbsp;

    </div>

</div>

<script>


</script>
