<?php
/**
 * Created by PhpStorm.
 * User: Lixuan
 * Date: 2019/1/21
 * Time: 13:48
 */
use yii\helpers\Url;

?>

<?=$this->render("/setting/main_setting",['tab'=>'mina_kuaidi'])?>

<div class="container" style="padding-left: 0px;">
    <div class="col-md-12" style="padding-left: 0px;">
        <div class="panel panel-default">
            <div class="panel-heading">
                <b>快递api设置</b>
            </div>
            <div class="panel-body">
                <table class="table">
                    <tr>
                        <td>
                            <form id="appForm" class="form-horizontal" role="form">
                                <div class="form-group">
                                    <label class="col-md-2 control-label" >快递EBusinessID </label>
                                    <div class="col-md-4">
                                        <input id="EBusinessID" type="text" class="form-control" value="<?=$array_api['EBusinessID']?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">APPKEY</label>
                                    <div class="col-md-4">
                                        <input id="appkey" type="text" class="form-control" value="<?=$array_api['AppKey']?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">REQURL</label>
                                    <div class="col-md-6">
                                        <input id="requrl" type="text" class="form-control" value="<?=$array_api['ReqURL']?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">快递访问url</label>
                                    <div class="col-md-4">
                                        <input id="kuaidi_url" type="text" class="form-control" value="<?=$array_api['kuaidi_url']?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-4 col-md-offset-3">
                                        <a id="save" class="btn btn-primary ladda-button" data-style="slide-up">保存</a>
                                    </div>
                                </div>
                            </form>

                        </td>
                    </tr>

                </table>

            </div>
        </div>

    </div>


</div>

<script type="text/javascript">
    $(function () {
        $('#save').bind('click', function () {
            var save_url = '?r=setting/kuaidisave';
            var EBusinessID = $('#EBusinessID').val();
            var appkey = $('#appkey').val();
            var requrl = $('#requrl').val();
            var kuaidi_url = $('#kuaidi_url').val();

            $.ajax({
                url: save_url,
                data: {
                    'EBusinessID': EBusinessID,
                    'appkey':appkey,
                    'requrl' : requrl,
                    'kuaidi_url': kuaidi_url,
                },
                dataType: 'json',
                type: 'POST',
                success: function (res) {
                    console.log(res)
                    alert(res.title)
                }
            })
        })
    })
</script>
