<?php
/**
 * Created by PhpStorm.
 * User: Lixuan
 * Date: 2019/1/16
 * Time: 10:14
 */
use yii\helpers\Url;

$this->title = '实时指标';
?>

<style>
    .btncls{margin:0 10px!important;}
</style>
<div class="panel panel-default">
    <div class="panel-heading">
        <p class="panel-title">
            实时指标
        </p>
    </div>
    <div class="panel-heading">
        <form id="myfrom" name="myform" action="">
            <table width="100%" class="table table-bordered">
                <a type="submit" class="btn btn-primary btncls" id="search" href="<?=Url::toRoute('/data/report') ?>"><i class="glyphicon glyphicon-search"></i> 订单报表  </a>
            </table>
        </form>
    </div>
    <div class="panel-body instant">
        <div class="container">

            <div class="row col-xs-12" >
                <div class="col-xs-4" style=" padding-left: 0;">
                    <div class="panel panel-default" style="background: #fff;">
                        <div class="panel-heading">
                            <p class="panel-title">
                                用户
                            </p>
                        </div>
                        <div class="panel-body wares">
                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">总访问数：</label>
                                <div class="col-sm-6 text-left">
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">昨日新增用户数：</label>
                                <div class="col-sm-6 text-left">
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">手机认证用户数：</label>
                                <div class="col-sm-6 text-left">
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">小程序用户数：</label>
                                <div class="col-sm-6 text-left">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-4" style=" padding-left: 0;">
                    <div class="panel panel-default" style="background: #fff;">
                        <div class="panel-heading">
                            <p class="panel-title">
                                历史订单数
                            </p>
                        </div>
                        <div class="panel-body wares">
                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">平台总订单：</label>
                                <div class="col-sm-6 text-left">

                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">系统自动关闭订单：</label>
                                <div class="col-sm-6 text-left">
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">已支付订单：</label>
                                <div class="col-sm-6 text-left">
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">待完成订单：</label>
                                <div class="col-sm-6 text-left">

                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">已取消订单：</label>
                                <div class="col-sm-6 text-left">

                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">异常订单：</label>
                                <div class="col-sm-6 text-left">

                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">重复下单客户：</label>
                                <div class="col-sm-6 text-left">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-4" style=" padding-left: 0;">
                    <div class="panel panel-default" style="background: #fff;">
                        <div class="panel-heading">
                            <p class="panel-title">
                                上周订单数
                            </p>
                        </div>
                        <div class="panel-body wares">

                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">平台总订单：</label>
                                <div class="col-sm-6 text-left">

                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">系统自动关闭订单：</label>
                                <div class="col-sm-6 text-left">

                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">已支付订单：</label>
                                <div class="col-sm-6 text-left">

                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">待完成订单：</label>
                                <div class="col-sm-6 text-left">

                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">已取消订单：</label>
                                <div class="col-sm-6 text-left">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            <div class="row col-xs-12" >
                <div class="col-xs-6" style=" padding-left: 0;">
                    <div class="panel panel-default" style="background: #fff;">
                        <div class="panel-heading">
                            <p class="panel-title">
                                用户订单比例
                            </p>
                        </div>
                        <div class="panel-body wares">
                            <div class="data_box">
                                <div class="data_box" id="data_box" style="height: 400px;width:750px;">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-6" style=" padding-left: 0;">
                    <div class="panel panel-default" style="background: #fff;">
                        <div class="panel-heading">
                            <p class="panel-title">
                                需求类型比例
                            </p>
                        </div>
                        <div class="panel-body wares">
                            <div class="data_box">
                                <div class="data_box" id="channels" style="height: 400px;width:750px;">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>

<script src="../../web/js/echarts.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        function getdevicedata(){
            //饼状图的配置
            var myChart = echarts.init(document.getElementById('data_box'));
            var option2 = {
                title : {
                    text: '用户设备类型比例',
                    subtext: '设备数量占比',
                    x:'center'
                },
                tooltip : {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    orient: 'vertical',
                    left: 'left',
                    data: [

                    ]
                },
                series : [
                    {
                        name: '访问来源',
                        type: 'pie',
                        radius : '55%',
                        center: ['50%', '60%'],
                        data:[

                        ],
                        itemStyle: {
                            emphasis: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }
                ]
            };
            myChart.setOption(option2);
        }

        function getChannels(){
            //饼状图的配置
            var myChart = echarts.init(document.getElementById('channels'));
            var option2 = {
                title : {
                    text: '报修渠道比例',
                    subtext: '订单量占比',
                    x:'center'
                },
                tooltip : {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    orient: 'vertical',
                    left: 'left',
                    data: [

                    ]
                },
                series : [
                    {
                        name: '访问来源',
                        type: 'pie',
                        radius : '55%',
                        center: ['50%', '60%'],
                        data:[

                        ],
                        itemStyle: {
                            emphasis: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }
                ]
            };
            myChart.setOption(option2);
        }


        getdevicedata();
        getChannels();
    });

</script>