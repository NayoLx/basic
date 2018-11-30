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

$this->title = '订单详情';
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
                    <td class="text-right">用户姓名：</td>
                    <td class="text-left"><input type="text" class="form-control" name="customer_name" id="customer_name" value="<?php if(!empty($gets['customer_name'])){ echo $gets['customer_name']; } ?>"></td>
                    <td class="text-right">用户电话：</td>
                    <td class="text-left"><input type="text" class="form-control" name="customer_phone" id="customer_phone" value="<?php if(!empty($gets['customer_phone'])){ echo $gets['customer_phone']; } ?>"></td>
                    <td class="text-right">用户学号：</td>
                    <td class="text-left"><input type="text" class="form-control" name="owner_name" id="owner_name" value="<?php if(!empty($gets['owner_name'])){ echo $gets['owner_name']; } ?>"></td>
                </tr>


                </tbody>
            </table>
        </form>
    </div>
</div>

</div>