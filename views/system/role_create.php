<?php
/**
 * Created by PhpStorm.
 * User: Lixuan
 * Date: 2019/1/18
 * Time: 16:14
 */
use yii\helpers\Url;


$this->title = '添加角色';
$this->params['breadcrumbs'][] =  '系统管理';
$this->params['breadcrumbs'][] = ['label' => '角色管理', 'url' => ['/system/role/index']];
$this->params['breadcrumbs'][] = $this->title;


?>

<form action=""  id="roleForm" method="post">
    <div class="panel panel-default">
        <div class="panel-body">
            <table  class="panel-box-tb">
                <thead>
                <tr>
                    <th width="150"></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="text-right">
                        <b class="required">角色名称：</b>
                    </td>
                    <td>
                        <input type="text" value="" name="name"  class="form-control required" style="width: 300px" >
                    </td>
                </tr>

                <tr>
                    <td class="text-right">
                        <b>角色描述：</b>
                    </td>
                    <td>
                        <textarea name="description"  class="form-control" style="width: 300px" rows="5"></textarea>
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <button type="submit" class="btn btn-primary" id="save">提&nbsp;&nbsp;交</button>&nbsp;&nbsp;&nbsp;&nbsp;
                        <a class="btn btn-default" href="">重&nbsp;&nbsp;&nbsp;&nbsp;置</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>

</form>

<script>
    // 表单提交验证
    seajs.use("base/1.0.0/unit/validate/custom-1.0.0",function  () {
        var validator = $("#roleForm").validate({
        });
    });

</script>