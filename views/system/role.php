<?php
/**
 * Created by PhpStorm.
 * User: Lixuan
 * Date: 2019/1/18
 * Time: 16:06
 */
use yii\helpers\Url;

$this->title = '角色管理';
$this->params['breadcrumbs'][] =  '系统管理';
$this->params['breadcrumbs'][] = $this->title;

?>


<div class="panel panel-default">
    <div class="panel-body">
        <form action="" method="get" class="filter-form">
            <table width="100%">
                <thead>
                <tr>
                    <th style="width:10%"></th>
                    <th style="width:40%"></th>
                    <th style="width:50%"></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><a class="btn btn-success" href=""><i class="glyphicon glyphicon-plus"></i> 添加角色</a></td>
                    <td>  <input type="text" class="form-control"   name="keyword"  value="" placeholder="输入角色名称" style="width:95%"></td>
                    <td>
                        <button type="submit" class="btn btn-primary">搜索</button>&nbsp;&nbsp;
                        <a href="" class="btn btn-default">重置</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>


<div class="panel panel-default">
    <div  class="panel-body">
        <?php if (!empty($roles)) : ?>
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th style="width:10%;">角色名称</th>
                    <th style="width:20%;">描述</th>
                    <th style="width:50%;">拥有权限</th>
                    <th style="width:20%;">操作</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <a href="">分配权限</a> &nbsp;|&nbsp;
                            <a href="">编辑</a> &nbsp;|&nbsp;
                            <a title="删除" href="javascript:void(0)">删除</a>

                        </td>
                    </tr>
                </tbody>
            </table>
        <?php else : ?>
            <p class="text-center">
                没有找到数据
            </p>
        <?php endif; ?>
    </div>

</div>

