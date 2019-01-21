<?php
/**
 * Created by PhpStorm.
 * User: Lixuan
 * Date: 2019/1/21
 * Time: 15:42
 */
use yii\helpers\Url;

?>

<?=$this->render("/setting/main_setting",['tab'=>'mina_kuaidilist'])?>

<div class="panel panel-default">
    <div class="panel-heading">
        <a href="<?=Url::toRoute(['setting/newkuaidi']) ?>" class="btn btn-success pull-left">添加</a>
        <div class="input-group " style="padding-left: 10px;">
            <input type="text" class="form-control" id='keyword' style="width:300px">
            <a class="btn btn-primary" id="search">搜索</a>
        </div>
    </div>

    <div  class="panel-body">
        <?php if (!empty($array_list)) : ?>
            <table class="table table-striped table-bordered"  id="brand-table">
                <thead>
                <tr>
                    <th style="width:5%;" class="text-center align-middle hqy-all-select">ID</th>
                    <th style="width:25%;">标签名</th>
                    <th style="width:20%;">简称</th>
                    <th style="width:30%;">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($array_list as $tag) : ?>
                    <tr>
                        <td class="text-center align-middle hqy-row-select"><?= $tag['id'] ?></td>
                        <td >
                            <?= $tag['k_name'] ?>
                        </td>
                        <td>
                            <?= $tag['value']?>
                        </td>
                        <td >
                            <a class="btn btn-info btn-sm btn_edit" href="<?=Url::toRoute(['setting/editkuaidi', 'id'=>$tag['id']]) ?>">编辑</a> &nbsp;&nbsp;
                            <a class="btn btn-danger btn-sm btn_delete" href="<?=Url::toRoute(['setting/deletekuaidi', 'id'=>$tag['id']]) ?>">删除</a> &nbsp;&nbsp;
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p class="text-center">
                没有找到数据
            </p>
        <?php endif; ?>
    </div>


</div>

<script type="text/javascript"> 
    $(function () {
        $('#search').bind('click', function () {
            var s_url = '?r=setting/kuaidilist';
            var keyword = $('#keyword').val();

            window.location.href = s_url + '&keyword=' + keyword;

            // $.ajax({
            //     url: s_url,
            //     type: 'POST',
            //     dataType: 'json',
            //     data: {
            //         'keyword': keyword
            //     },
            //     success:function (res) {
            //         location.reload(true);
            //     }
            // })
        })
    })
</script>