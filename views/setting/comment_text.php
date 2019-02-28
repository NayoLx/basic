<?php
/**
 * Created by PhpStorm.
 * User: Lixuan
 * Date: 2019/2/28
 * Time: 12:03
 */
use yii\helpers\Url;

?>

<?=$this->render("/setting/main_setting",['tab'=>'mina_commentlist'])?>

<div class="panel panel-default">
    <div class="panel-heading">
        <a href="<?=Url::toRoute(['setting/newcomment']) ?>" class="btn btn-success ">添加</a>
        <div class="input-group " style="padding-left: 10px;">
<!--            <input type="text" class="form-control" id='keyword' style="width:300px" pull-left>-->
<!--            <a class="btn btn-primary" id="search">搜索id</a>-->
        </div>
    </div>

    <div  class="panel-body">
            <table class="table table-striped table-bordered"  id="brand-table">
                <thead>
                <tr>
                    <th style="width:5%;" class="text-center align-middle hqy-all-select">ID</th>
                    <th style="width:40%;">文章名</th>
                    <th style="width:20%;">作者</th>
                    <th style="width:15%;">发布时间</th>
                    <th style="width:20%;">操作</th>
                </tr>
                </thead>
                <?php if (!empty($array_list)) : ?>
                <tbody>
                <?php foreach ($array_list as $tag) : ?>
                        <tr>
                            <td class="text-center align-middle hqy-row-select"><?= $tag['id'] ?></td>
                            <td >
                                <?= $tag['title'] ?>
                            </td>
                            <td>
                                <?= $tag['author']?>
                            </td>
                            <td>
                                <?= $tag['data']?>
                            </td>
                            <td >
                                <a class="btn btn-info btn-sm btn_edit" href="<?=Url::toRoute(['setting/editcomment', 'id'=>$tag['id']]) ?>">编辑</a> &nbsp;&nbsp;
                                <a class="btn btn-danger btn-sm btn_delete" href="<?=Url::toRoute(['setting/deletecomment', 'id'=>$tag['id']]) ?>">删除</a> &nbsp;&nbsp;
                            </td>
                        </tr>
                <?php endforeach; ?>

                </tbody>
                <?php else : ?>
                    <p class="text-center">
                        没有找到数据
                    </p>
                <?php endif; ?>
            </table>
    </div>

</div>

<script type="text/javascript">
    $(function () {
        $('#search').bind('click', function () {
            window.location.href = '?r=setting/commentlist&keyword=' + $('#keyword').val();
        })
    })
</script>