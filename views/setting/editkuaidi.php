<?php
/**
 * Created by PhpStorm.
 * User: Lixuan
 * Date: 2019/1/21
 * Time: 16:21
 */
use yii\helpers\Url;
?>

<?=$this->render("/setting/main_setting",['tab'=>'mina_kuaidilist'])?>


<div class="panel panel-default">
    <div style="font-size: 23px; text-align: center; margin: 10px 0 0 0;">----修改信息----</div>
    <div class="panel-body ">

        <div class="form-group col-sm-12">
            <label for="skillName" class="col-sm-4 control-label text-right">名称：</label>
            <div class="col-sm-4 text-left">
                <input type="text"  class="form-control col-sm-3" id="tagName" placeholder="请输入名称" value="<?=$choose['name']?>">
            </div>
        </div>
        <div class="form-group col-sm-12">
            <label for="skillName" class="col-sm-4 control-label text-right">编码：</label>
            <div class="col-sm-4 text-left">
                <input type="text"  class="form-control col-sm-3" id="tagvalue" placeholder="请输入编码" value="<?=$choose['value']?>">
            </div>
        </div>
    </div>

    <div class="panel-footer text-center">
        <button type="button" class="btn btn-primary" id="save">修   改</button>
    </div>
</div>


<script>
    $(document).ready(function () {
        $("#save").bind("click",function () {
            var kTagName = $("#tagName").val();
            var kTagvalue = $("#tagvalue").val();
            if(kTagName == null || kTagName == "" || kTagvalue == ''){
                alert("请填写标签名称");
                $("#tagName").focus();
                return false;
            }
            $.ajax({
                type: "post",
                url: "?r=setting/editnew",
                dataType:"json",
                data: {
                    'kTagName': kTagName,
                    'kTagvalue': kTagvalue,
                },
                success:function(res){
                    console.log(res)
                },
                error:function(res){
                    //提示确认失败
                }
            });
        });
    });
</script>
