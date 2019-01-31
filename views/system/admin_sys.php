<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2019/1/18
 * Time: 15:57
 */
use yii\helpers\Url;

$this->title =  "账号管理";
$this->params['breadcrumbs'][] = '角色管理';
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="col-md-12" style="padding:0px 5px 10px 0px;">
            <form action="" method="get" class="filter-form">
                <table>
                    <tr>
                        <td width="5%">
                            <a href="<?=Url::toRoute('system/admincreate')?>" class="btn btn-success">新建账号</a>
                        </td>
                        <td width="1%"></td>
                        <td width="40%">
                            <div class="input-group">
                                <input type="text" class="form-control" name="keyword"  value="" style="width:300px">
                                <a type="submit" class="btn btn-primary" >搜索</a>
                            </div>
                        </td>
                        <td width="53%">&nbsp;</td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-body">
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th width="5%">id</th>
                <th width="10%">用户名</th>
                <th width="15%">角色</th>
                <th width="15%">邮箱</th>
                <th width="8%">姓名</th>
                <th width="10%">手机</th>
                <th width="7%">是否启用</th>
                <th width="15%">操作</th>
            </tr>
            </thead>
            <?php if($admin_list):?>
            <?php foreach($admin_list as $key => $item):?>
            <tbody>
                <tr>
                    <td style="padding:12px;">
                        <?=$key+1?>
                    </td>
                    <td style="padding:12px;">
                        <?=$item['username']?>
                    </td>
                    <td style="padding:12px;">
                        <?=$item['role']?>
                    </td>
                    <td style="padding:12px;">
                        <?=$item['e-mail']?>
                    </td>
                    <td style="padding:12px;">
                        <?=$item['name']?>
                    </td>
                    <td style="padding:12px;">
                        <?=$item['phone']?>
                    </td>
                    <td style="padding:12px;">
                        <?php if($item['is_close'] == 'false') {
                            echo '已启用';
                        } else { echo '禁用'; }?>
                    </td>

                    <td style="padding:12px;">
                        <a href=""><span class="btn btn-success" style="padding:8px;">设置角色</span></a> &nbsp;&nbsp;
                        <a  href="<?=Url::toRoute(['system/adminsetting', 'id' => $item['id']])?>"><span class="btn btn-primary" style="padding:8px;">编辑</span></a> &nbsp;&nbsp;
                    </td>
                </tr>
            </tbody>
            <?php endforeach;?>
            <?php endif;?>
        </table>
    </div>
</div>