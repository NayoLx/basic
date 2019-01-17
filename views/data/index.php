<?php
/**
 * Created by PhpStorm.
 * User: Lixuan
 * Date: 2019/1/16
 * Time: 10:14
 */
use yii\helpers\Url;
use yii\helpers\Html;

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
                <a type="submit" class="btn btn-primary btncls" id="search" href="<?=Url::toRoute(['/data/report', 'type'=> 1]) ?>"><i class="glyphicon glyphicon-search"></i> 订单报表  </a>
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
                                    <?php if($client_data['all']) {
                                        echo $client_data['all'];
                                    } else { echo "0";} ?>
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">昨日新增用户数：</label>
                                <div class="col-sm-6 text-left">
                                    <?php if($client_data['last_week_new']) {
                                        echo $client_data['last_week_new'];
                                    } else { echo "0";} ?>
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">手机认证用户数：</label>
                                <div class="col-sm-6 text-left">
                                    <?php if($client_data['phone_is_bind']) {
                                        echo $client_data['phone_is_bind'];
                                    } else { echo "0";} ?>
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">小程序用户数：</label>
                                <div class="col-sm-6 text-left">
                                    <?php if($client_data['mina_user']) {
                                        echo $client_data['mina_user'];
                                    } else { echo "0";} ?>
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
                                    <?php if($history_order_data['all_order']) {
                                        echo $history_order_data['all_order'];
                                    } else { echo "0";} ?>
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">系统自动关闭订单：</label>
                                <div class="col-sm-6 text-left">
                                    <?php if($history_order_data['system_close'] ) {
                                        echo $history_order_data['system_close'] ;
                                    } else { echo "0";} ?>
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">待完成订单：</label>
                                <div class="col-sm-6 text-left">
                                    <?php if($history_order_data['nofinish_order']) {
                                        echo $history_order_data['nofinish_order'];
                                    } else { echo "0";} ?>
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">已取消订单：</label>
                                <div class="col-sm-6 text-left">
                                    <?php if($history_order_data['close_order']) {
                                        echo $history_order_data['close_order'];
                                    } else { echo "0";} ?>
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">异常订单：</label>
                                <div class="col-sm-6 text-left">
                                    <?php if($history_order_data['issue_order'] ) {
                                        echo $history_order_data['issue_order'] ;
                                    } else { echo "0";} ?>
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">重复下单客户：</label>
                                <div class="col-sm-6 text-left">
                                    <?php if($history_order_data['issue_order'] ) {
                                        echo $hhistory_order_data['issue_order'] ;
                                    } else { echo "0";} ?>
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
                                    0
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">系统自动关闭订单：</label>
                                <div class="col-sm-6 text-left">
                                    0
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">已支付订单：</label>
                                <div class="col-sm-6 text-left">
                                    0
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">待完成订单：</label>
                                <div class="col-sm-6 text-left">
                                    0
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="skillName" class="col-sm-6 control-label text-right">已取消订单：</label>
                                <div class="col-sm-6 text-left">
                                    0
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
                                <div class="data_box" id="data_box" style="height: 400px;width:450px;">

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
                                <div class="data_box" id="channels" style="height: 400px;width:450px;">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="/basic/web/js/echarts.min.js" type="text/javascript"></script>
<script src="/basic/web/js/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" >
    $(document).ready(function() {
        function getdevicedata(){
            //饼状图的配置
            var myChart = echarts.init(document.getElementById('data_box'));
            var option2 = {

                title : {
                    text: '用户订单比例',
                    x:'center'
                },
                tooltip : {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                visualMap: {
                    show: false,
                    min: 80,
                    max: 600,
                    inRange: {
                        colorLightness: [0, 1]
                    }
                },
                series : [
                    {
                        name: '访问来源',
                        type: 'pie',
                        radius : '55%',
                        center: ['50%', '60%'],
                        data:[
                            {name: 'A', value: 1212},
                            {name: 'B', value: 2323},
                            {name: 'C', value: 1919}
                        ].sort(function (a, b) { return a.value - b.value; }),
                        roseType: 'radius',
                        label: {
                            normal: {
                                textStyle: {
                                    color: 'black'
                                }
                            }
                        },
                        labelLine: {
                            normal: {
                                lineStyle: {
                                    color: 'black'
                                },
                                smooth: 0.2,
                                length: 10,
                                length2: 20
                            }
                        },
                        itemStyle: {
                            normal: {
                                shadowBlur: 200,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        },
                        animationType: 'scale',
                        animationEasing: 'elasticOut',
                        animationDelay: function (idx) {
                            return Math.random() * 200;
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
                    text: '需求类型比例',
                    x:'center'
                },
                tooltip : {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                visualMap: {
                    show: false,
                    min: 80,
                    max: 600,
                    inRange: {
                        colorLightness: [0, 1]
                    }
                },
                series : [
                    {
                        name: '访问来源',
                        type: 'pie',
                        radius : '55%',
                        center: ['50%', '60%'],
                        data:[
                            <?php if($order_type_chart) {
                                foreach ($order_type_chart as $key => $item) {
                                    echo "{name: '{$item['name']}', value: {$item['value']}},";
                                }
                        }?>
                        ].sort(function (a, b) { return a.value - b.value; }),
                        roseType: 'radius',
                        label: {
                            normal: {
                                textStyle: {
                                    color: 'black'
                                }
                            }
                        },
                        labelLine: {
                            normal: {
                                lineStyle: {
                                    color: 'black'
                                },
                                smooth: 0.2,
                                length: 10,
                                length2: 20
                            }
                        },
                        itemStyle: {
                            normal: {
                                shadowBlur: 200,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        },
                        animationType: 'scale',
                        animationEasing: 'elasticOut',
                        animationDelay: function (idx) {
                            return Math.random() * 200;
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