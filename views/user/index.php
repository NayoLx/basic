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
use yii\helpers\Url;

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
                    <td width="20%" class="text-left"><input type="text" class="form-control" name="user_name" id="user_name" value="<?=$gets['user_name']?>"></td>
                    <td width="10%"  class="text-right">用户学号：</td>
                    <td width="20%" class="text-left"><input type="text" class="form-control" name="user_id" id="user_id" value="<?=$gets['user_id']?>"></td>
                    <td class="text-right">用户电话：</td>
                    <td class="text-left"><input type="text" class="form-control" name="phone" id="phone" value="<?=$gets['phone']?>"></td>
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

    <div class="panel panel-default">
        <div  class="panel-body">

            <table class="table table-striped table-bordered"  id="brand-table">
                <thead>
                <tr>
                    <th style="width:10%;">学生id</th>
                    <th style="width:10%">头像</th>
                    <th style="width:10%;">学号</th>
                    <th style="width:10%;">姓名</th>
                    <th style="width:25%;">专业</th>
                    <th style="width:20%;">学校邮箱</th>
                    <th style="width:15%;">操作</th>
                </tr>
                </thead>
                <?php if ($user_list != ''): ?>
                <?php foreach ($user_list as $key => $item): ?>
                <tr>
                    <td class="text-center align-middle hqy-row-select"><?=$key+1?></td>
                    <td >
<!--                        --><?//=$item['stunumber']?>
                    </td>
                    <td >
                        <?=$item['stunumber']?>
                    </td>
                    <td >
                        <?=$item['stuname']?>
                    </td>
                    <td>
                        <?=$item['major']?>
                    </td>
                    <td >
                        <?=$item['schoolemail']?>
                    </td>
                    <td >
                        <a class="btn btn-primary" style="margin: 4px;" id = "todetail"  href = "<?php echo Url::toRoute(['user/info', 'id' => $item['stunumber']])?>">查看详情</a>

                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
            </table>
            <p class="text-center">
                没有找到数据
            </p>

            <?php endif; ?>
        </div>

    </div>

<script type="text/javascript">
    $(function () {
        $('#search').bind("click", function () {
            var export_url = "?r=user/index&user_name="+$("#user_name").val()+"&user_id="+$("#user_id").val() + "&phone=" + $("#phone").val();
                window.location.href = export_url;
        })
    })


</script>
