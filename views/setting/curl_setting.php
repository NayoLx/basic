<?php
/**
 * Created by PhpStorm.
 * User: Lixuan
 * Date: 2019/1/21
 * Time: 14:10
 */
use yii\helpers\Url;

?>

<?=$this->render("/setting/main_setting",['tab'=>'mina_curl'])?>

<div class="container" style="padding-left: 0px;">
<div class="col-md-12" style="padding-left: 0px;">
    <div class="panel panel-default">
        <div class="panel-heading">
            <b>本地爬虫地址设置</b>
        </div>
        <div class="panel-body">
            <table class="table">
                <tr>
                    <td>
                        <form id="appForm" class="form-horizontal" role="form">
                            <div class="form-group">
                                <label class="col-md-2 control-label" >ip地址 </label>
                                <div class="col-md-4">
                                    <input id="ip_address" type="text" class="form-control" value="<?=$array_url['ip']?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">url</label>
                                <div class="col-md-4">
                                    <input id="c_url" type="text" class="form-control" value="<?=$array_url['url']?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">loginurl</label>
                                <div class="col-md-8">
                                    <input id="c_loginurl" type="text" class="form-control" value="<?=$array_url['loginUrl']?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">schedularurl</label>
                                <div class="col-md-8">
                                    <input id="c_surl" type="text" class="form-control" value="<?=$array_url['schedularUrl']?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">indexurl</label>
                                <div class="col-md-8">
                                    <input id="c_indexurl" type="text" class="form-control" value="<?=$array_url['indexUrl']?>">
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
            var save_url = '?r=setting/curlsave';
            var ip = $('#ip_address').val();
            var url = $('#c_url').val();
            var loginurl = $('#c_loginurl').val();
            var schedualrurl = $('#c_surl').val();
            var indexurl = $('#c_indexurl').val();

            $.ajax({
                url: save_url,
                data: {
                    'ip': ip,
                    'url': url,
                    'loginurl' : loginurl,
                    'schedualrurl': schedualrurl,
                    'indexurl': indexurl,
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
