<?php
/**
 * Created by PhpStorm.
 * User: Lixuan
 * Date: 2019/2/28
 * Time: 15:24
 */
use yii\helpers\Url;

?>

<?=$this->render("/setting/main_setting",['tab'=>'mina_comment'])?>


<div class="panel panel-default">
    <div style="font-size: 23px; text-align: center; margin: 10px 0 0 0;">----修改文章----</div>
    <div class="panel-body ">
        <div class="form-group col-sm-12" style="display: none">
            <label for="skillName" class="col-sm-4 control-label text-right">id：</label>
            <div class="col-sm-4 text-left">
                <input type="text"  class="form-control col-sm-3" id="tagid" value="<?=$detail['id']?>" disabled="true">
            </div>
        </div>
        <div class="form-group col-sm-12">
            <label for="skillName" class="col-sm-4 control-label text-right">标题：</label>
            <div class="col-md-6  text-left">
                <input type="text"  class="form-control col-sm-3" id="texttitle" placeholder="请输入名称" value="<?=$detail['title']?>">
            </div>
        </div>
        <div class="form-group col-sm-12">
            <label for="skillName" class="col-sm-4 control-label text-right">文章：</label>
            <div class="col-md-6  text-left">
                <textarea style="width: 100%; height: 500px" id="textcomment"><?=$detail['detail']?></textarea>
            </div>
        </div>

    </div>

    <div class="panel-footer text-center">
        <button type="button" class="btn btn-primary" id="save">修&nbsp;&nbsp;&nbsp;&nbsp;改</button>&nbsp;&nbsp;&nbsp;&nbsp;
    </div>
</div>


<script>
    $(document).ready(function () {
        $("#save").bind("click", function () {
            var id = $("#tagid").val();
            var texttitle = $("#texttitle").val();
            var textcomment = $("#textcomment").val();
            if(texttitle == null || texttitle == "" || textcomment == ''){
                alert("请填写标题");
                $("#textcomment").focus();
                return false;
            }
            $.ajax({
                type: "post",
                url: "?r=setting/editcommentac",
                dataType:"json",
                data: {
                    'id': id,
                    'texttitle': texttitle,
                    'textcomment': textcomment,
                },
                success:function(res){
                    console.log(res)
                    if(res.success) {
                        alert(res.title)
                        window.location.href = '?r=setting/commentlist'
                    }
                    else {
                        alert(res.error)
                    }
                },
                error:function(res){
                    //提示确认失败
                }
            });
        });
    });
</script>