<?php
/**
 * Created by PhpStorm.
 * User: Lixuan
 * Date: 2018/1/16
 * Time: 10:35
 ***************************
 * Report 报表信息数据
 *
 ***************************
 */

namespace app\controllers;

use Yii;
use yii\db\Exception;
use yii\web\Controller;
use app\helpers\Utils;

class DataController extends Controller
{
    public function actionIndex()
    {
    	$params = [];
    	$client_data = [];
    	$history_order_data = [];
    	$last_week_order = [];

    	/**用户*/
    	$all = Yii::$app->db->createCommand('select * from user_student')->queryAll();
    	$last_week_new = Yii::$app->db->createCommand("select * from
    		user_student where last_time between '2019/1/10' and '2019/1/17' ")->queryAll();
    	$phone_is_bind = Yii::$app->db->createCommand('select * from wxdeatil where is_bind = :is_bind')->bindValue(':is_bind', 'true')->queryAll();
    	$mina_user = Yii::$app->db->createCommand('select * from wxdeatil')->queryAll();

    	$client_data['all'] = count($all);
    	$client_data['last_week_new'] = count($last_week_new);
    	$client_data['phone_is_bind'] = count($phone_is_bind);
    	$client_data['mina_user'] = count($mina_user);

    	/**历史订单数*/
    	$all_order = Yii::$app->db->createCommand('select * from order_detail')->queryAll();
    	$system_close = Yii::$app->db->createCommand('select * from order_detail where status = :status')->bindValue(':status', '6')->queryAll();
    	$nofinish_order = Yii::$app->db->createCommand("select * from order_detail where status = '2' or status = '3'")->queryAll();
    	$close_order = Yii::$app->db->createCommand("select * from order_detail where status = '5'")->queryAll();
    	$issue_order = Yii::$app->db->createCommand("select * from order_detail where status = '7' ")->queryAll();

    	$history_order_data['all_order'] = count($all_order);
    	$history_order_data['system_close'] = count($system_close);
    	$history_order_data['nofinish_order'] = count($nofinish_order);
    	$history_order_data['close_order'] = count($close_order);
    	$history_order_data['issue_order'] = count($issue_order);

    	/**上周订单数*/

    	$client_chart = [];

    	/*订单类型饼图*/
    	$need_order = Yii::$app->db->createCommand("select * from order_detail where type = 'N' ")->queryAll();
    	$Help_order = Yii::$app->db->createCommand("select * from order_detail where type = 'T' ")->queryAll();
    	$other_order = Yii::$app->db->createCommand("select * from order_detail where type = 'O' ")->queryAll();

    	$order_type_chart = [
    	    ['name'=>'需要', 'value'=>count($need_order)],
            ['name'=>'可帮', 'value'=>count($Help_order)],
            ['name'=>'其他', 'value'=>count($other_order)],
        ];


    	$params['client_data'] = $client_data;
    	$params['history_order_data'] = $history_order_data;
    	$params['order_type_chart'] = $order_type_chart;

       return $this->render('index', $params);
    }

    public function actionReport()
    {
    	$params = [];
    	/*type = 1 最近7日 type = 2 最近30日*/
    	$type = Yii::$app->request->get('type', '');
    	$start_at = Yii::$app->request->get('start', '');
    	$end_at = Yii::$app->request->get('end', '');

    	if ($type == '1') {
    		$week_time = $this->get_weeks();

    		$start_at = date('Y-m-d', strtotime('-6 day'));
    		$end_at = date('Y-m-d');
    		echo $start_at;
    	    for ( $i = 1; $i <= 7; $i++ ) {
    	        $chart_data_all[$i] = Yii::$app->db->createCommand("select * from order_detail where push_time between '$week_time[$i] 00:00:00' and '$week_time[$i] 23:59:59'")->queryAll();
    	        $chart_data_success[$i] = Yii::$app->db->createCommand("select * from order_detail where push_time between '$week_time[$i] 00:00:00' and '$week_time[$i] 23:59:59' and status = '4'")->queryAll();
    	        $chart_data_doing[$i] = Yii::$app->db->createCommand("select * from order_detail where push_time between '$week_time[$i] 00:00:00' and '$week_time[$i] 23:59:59' and status = '2' or status = '3'")->queryAll();
    	        $chart_data_close[$i] = Yii::$app->db->createCommand("select * from order_detail where push_time between '$week_time[$i] 00:00:00' and '$week_time[$i] 23:59:59' and status = '5'")->queryAll();

    	        $chart_report[$i] = ['week_time' => $week_time[$i], 'chart_data_all' => $chart_data_all[$i], 'chart_data_success' => $chart_data_success[$i], 'chart_data_doing' => $chart_data_doing[$i], 'chart_data_close' => $chart_data_close[$i]];
    	    }
    	}
    	if ($type == '2') {
    		$week_time = $this->get_month();
            $start_at = date('Y-m-d', strtotime('-29 day'));
            $end_at = date('Y-m-d');

            for ( $i = 1; $i <= 30; $i++ ) {
    	        $chart_data_all[$i] = Yii::$app->db->createCommand("select * from order_detail where push_time between '$week_time[$i] 00:00:00' and '$week_time[$i] 23:59:59'")->queryAll();
    	        $chart_data_success[$i] = Yii::$app->db->createCommand("select * from order_detail where push_time between '$week_time[$i] 00:00:00' and '$week_time[$i] 23:59:59' and status = '4'")->queryAll();
    	        $chart_data_doing[$i] = Yii::$app->db->createCommand("select * from order_detail where push_time between '$week_time[$i] 00:00:00' and '$week_time[$i] 23:59:59' and status = '2' or status = '3'")->queryAll();
    	        $chart_data_close[$i] = Yii::$app->db->createCommand("select * from order_detail where push_time between '$week_time[$i] 00:00:00' and '$week_time[$i] 23:59:59' and status = '5'")->queryAll();

    	        $chart_report[$i] = ['week_time' => $week_time[$i], 'chart_data_all' => $chart_data_all[$i], 'chart_data_success' => $chart_data_success[$i], 'chart_data_doing' => $chart_data_doing[$i], 'chart_data_close' => $chart_data_close[$i]];
    	    }
    	}
    	if ($type == '3') {
    		$datetime_start = date_create($start_at);
            $datetime_end = date_create($end_at);
            $days = date_diff($datetime_start, $datetime_end)->days;

            $week_time = $this->get_time_bt($start_at, $end_at);
            for ( $i = 0; $i <= $days; $i++ ) {
    	        $chart_data_all[$i] = Yii::$app->db->createCommand("select * from order_detail where push_time between '$week_time[$i] 00:00:00' and '$week_time[$i] 23:59:59'")->queryAll();
    	        $chart_data_success[$i] = Yii::$app->db->createCommand("select * from order_detail where push_time between '$week_time[$i] 00:00:00' and '$week_time[$i] 23:59:59' and status = '4'")->queryAll();
    	        $chart_data_doing[$i] = Yii::$app->db->createCommand("select * from order_detail where push_time between '$week_time[$i] 00:00:00' and '$week_time[$i] 23:59:59' and status = '2' or status = '3'")->queryAll();
    	        $chart_data_close[$i] = Yii::$app->db->createCommand("select * from order_detail where push_time between '$week_time[$i] 00:00:00' and '$week_time[$i] 23:59:59' and status = '5'")->queryAll();

    	        $chart_report[$i] = ['week_time' => $week_time[$i], 'chart_data_all' => $chart_data_all[$i], 'chart_data_success' => $chart_data_success[$i], 'chart_data_doing' => $chart_data_doing[$i], 'chart_data_close' => $chart_data_close[$i]];
    	    }
    	}

    	$params['chart_data_all'] = $chart_data_all;
    	$params['chart_data_success'] = $chart_data_success;
    	$params['chart_data_doing'] = $chart_data_doing;
    	$params['chart_data_close'] = $chart_data_close;
    	$params['week_time'] = $week_time;
    	$params['chart_report'] = $chart_report;
 
    	$params['startTime'] = $start_at;
    	$params['endTime'] = $end_at;

    	return $this->render('report', $params);
    }

    /**
     * 获取最近7天所有日期
     */
    static function get_weeks($time = '', $format='Y-m-d'){
      $time = $time != '' ? $time : time();
      //组合数据
      $date = [];
      for ($i=1; $i<=7; $i++){
        $date[$i] = date($format ,strtotime( '+' . $i-7 .' days', $time));
      }
      return $date;
    }   
    /**
     * 获取最近30天所有日期
     */
    static function get_month($time = '', $format='Y-m-d'){
      $time = $time != '' ? $time : time();
      //组合数据
      $date = [];
      for ($i=1; $i<=30; $i++){
        $date[$i] = date($format ,strtotime( '+' . $i-30 .' days', $time));
      }
      return $date;
    }  

    /**
     * 两个时间相差的时间差
     */
    static function get_time_bt($start_at, $end_at) {
    	$datetime_start = date_create($start_at);
        $datetime_end = date_create($end_at);
        $days = date_diff($datetime_start, $datetime_end)->days;
        $date = [];

        for ($i = 0; $i <= $days; $i++) {
           $date[$i] = date("Y-m-d",strtotime("+".$i." day",strtotime($start_at)));
        }
        return $date;
    }
} 