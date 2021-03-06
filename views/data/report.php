<?php
/**
 * Created by PhpStorm.
 * User: Lixuan
 * Date: 2019/1/16
 * Time: 10:16
 */
use yii\helpers\Url;

$this->title = '订单报表';
$this->params['breadcrumbs'][] = '数据分析';
$this->params['breadcrumbs'][] = ['label' => '数据中心', 'url' => ['/data/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="panel panel-default">
    <div class="panel-heading">
        <p class="panel-title">
            订单报表
        </p>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <style>
                #body_warps {
                    margin: 50px 100px 0px 100px;
                }

                .top {
                    width: 100%;
                    height: 45px;
                    padding-left: 25px;
                }

                .top div {
                    float: left;
                    margin-left: 3px;
                }

                .top-right div {
                    float: left;
                }

                button {
                    outline: none;
                    cursor: pointer;
                    -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
                }

                .top button {
                    background-color: #FFFFFF;
                    padding: 0px 15px;
                    margin-right: 15px;
                    border: 1px solid #CFCFCF;
                    height: 35px;
                    float: left;
                    color: #333333;
                }
            </style>
            <div id="body_warps">
                <form action="" method="get">
                    <div class="top">
                        <div clas="top-left">
                            <a href="<?= Url::toRoute(['data/report', 'type' => 1]) ?>" class="btn btn-success">最近7天</a>
                            <a href="<?= Url::toRoute(['data/report', 'type' => 2]) ?>" class="btn btn-success">最近30天</a>
                        </div>
                        <div clas="top-right">
                            <div>
                                <input type="date" class="form-control" name="time_start" id='time_start' value="<?php if ($startTime) {echo date('Y-m-d', time($startTime));} ?>">
                            </div>
                            <div><span> _ </span></div>
                            <div>
                                <input type="date" class="form-control" name="time_end"  id='time_end' value="<?php if ($endTime) {echo date('Y-m-d', time($endTime));} ?>">
                            </div>
                            <div>
                                <a class="form-control btn btn-success" id="submit" >查询</a>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="data_box">
                    <div class="data_box" id="data_box" style="height: 400px;width:810px;">

                    </div>
                </div>
            </div>
        </div>

        <div class="panel-body" id="auth_success">
            <table class="table">
                <tr>
                    <td colspan="4"><span style="color: #5bc0de;"></span><b>详细列表</b></td>

                    <td class="text-right">
                        <input class="btn btn-primary" id="btn_export" type="button" value="导出(xlsx)">
                    </td>
                </tr>
                <tr>
                    <th width="20%">日期</th>
                    <th width="20%">订单总数</th>
                    <th width="20%">完成订单数</th>
                    <th width="20%">待完成订单数</th>
                    <th width="20%">取消订单数</th>
                </tr>
                <?php if($chart_report):?>
                	<?php foreach($chart_report as $key => $item): ?>
                <tr>
                	<td><?=$item['week_time']?></td>
                	<td><?=count($item['chart_data_all'])?></td>
                	<td><?=count($item['chart_data_success'])?></td>
                	<td><?=count($item['chart_data_doing'])?></td>
                	<td><?=count($item['chart_data_close'])?></td>
                </tr>
                    <?php endforeach; ?>
                <?php endif;?>
            </table>
        </div>

    </div>
</div>

<script src="/basic/web/js/echarts.min.js" type="text/javascript"></script>
<script src="/basic/web/js/jquery.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function () {
        function getdata() {
            //饼状图的配置
            var myChart = echarts.init(document.getElementById('data_box'));
            var option2 = {
                title: {
                    text: '订单趋势图',
                    top: '0%',
                    left: '50%',
                    margin_left: '-40px',
                    textAlign: 'center'
                },
                tooltip: {
                    trigger: 'axis'

                },
                legend: {
                    data: ['总订单', '成功订单', '待完成订单', '取消订单'],
                    bottom: '0px'
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '45px',
                    containLabel: true
                },
                toolbox: {
                    feature: {
                        saveAsImage: {}
                    }
                },
                xAxis: {
                    type: 'category',
                    boundaryGap: false,
                    data: [<?php foreach ($week_time as $key => $value) {
            		                echo "'".$value."',";
            	               }?>]
                },
                yAxis : [
                    {
                        type: 'value',
                        name: '订单数据',
                        position: 'left',
                        axisLabel: {
                            formatter: '{value}'
                        }
                    }
                ],
                series: [
                    {
                        name: '总订单',
                        type: 'line',
                        itemStyle: {
                            normal: {
                                color: "#FF0000",
                                lineStyle: {
                                    color: "#FF0000"
                                }
                            }
                        },
                        stack: '总订单',
                        data: [<?php foreach ($chart_data_all as $key => $value) {
            		                $str = count($value) ;
            		                echo "'".$str."',";
            	               }?>]
                    },
                    {
                        name: '成功订单',
                        type: 'line',
                        itemStyle: {
                            normal: {
                                color: "#00BFFF",
                                lineStyle: {
                                    color: "#00BFFF"
                                }
                            }
                        },
                        stack: '成功订单',
                        data: [<?php foreach ($chart_data_success as $key => $value) {
            		                $str = count($value) ;
            		                echo "'".$str."',";
            	               }?>]
                    },
                    {
                        name: '待完成订单',
                        type: 'line',
                        itemStyle: {
                            normal: {
                                color: "#FF8C00",
                                lineStyle: {
                                    color: "#FF8C00"
                                }
                            }
                        },
                        stack: '待完成订单',
                        data: [<?php foreach ($chart_data_doing as $key => $value) {
            		                $str = count($value) ;
            		                echo "'".$str."',";
            	               }?>]
                    },
                    {
                        name: '取消订单',
                        type: 'line',
                        itemStyle: {
                            normal: {
                                color: "#708090",
                                lineStyle: {
                                    color: "#708090"
                                }
                            }
                        },
                        stack: '取消订单',
                        data: [<?php foreach ($chart_data_close as $key => $value) {
            		                $str = count($value) ;
            		                echo "'".$str."',";
            	               }?>]
                    }
                ]
            };
            myChart.setOption(option2);
        }

        getdata();

        $('.top button').click(function () {
            getdata();
        })

        $("#submit").bind("click", function () {
            var export_url = "?r=data/report&type=3&start="+$("#time_start").val()+"&end="+$("#time_end").val();
            window.location.href = export_url;
        });

    });
</script>