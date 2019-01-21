<?php
/**
 * Created by PhpStorm.
 * User: Lixuan
 * Date: 2019/1/21
 * Time: 14:11
 */
use yii\helpers\Url;

?>

<?=$this->render("/setting/main_setting",['tab'=>'mina_baidu'])?>

<div class="container" style="padding-left: 0px;">
    <div class="col-md-12" style="padding-left: 0px;">
        <div class="panel panel-default">
            <div class="panel-heading">
                <b>百度api设置</b>
            </div>
            <div class="panel-body">
                <table class="table">
                    <tr>
                        <td>
                            <form id="appForm" class="form-horizontal" role="form">
                                <div class="form-group">
                                    <label class="col-md-2 control-label" >appid </label>
                                    <div class="col-md-4">
                                        <input id="appid" type="text" class="form-control" value="<?=$array_api['AppID']?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">百度key</label>
                                    <div class="col-md-4">
                                        <input id="apikey" type="text" class="form-control" value="<?=$array_api['API Key']?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">百度secret key</label>
                                    <div class="col-md-8">
                                        <input id="sckey" type="text" class="form-control" value="<?=$array_api['Secret Key']?>">
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
            var save_url = '?r=setting/baidusave';
            var appid = $('#appid').val();
            var apikey = $('#apikey').val();
            var sckey = $('#sckey').val();

            $.ajax({
                url: save_url,
                data: {
                    'appid': appid,
                    'apikey': apikey,
                    'sckey' : sckey,
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
