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
            <b>本地爬虫url设置</b>
        </div>
        <div class="panel-body">
            <table class="table">
                <tr>
                    <td>
                        <form id="appForm" class="form-horizontal" role="form">
                            <div class="form-group">
                                <label class="col-md-2 control-label" > API域名</label>
                                <div class="col-md-4">
                                    <input id="domain" type="text" class="form-control" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">支付异步通知域名</label>
                                <div class="col-md-4">
                                    <input id="payDomain" type="text" class="form-control" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3">
                                    <button id="appSubmit" class="btn btn-primary ladda-button" data-style="slide-up">保存</button>
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
