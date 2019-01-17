<?php
/**
 * Created by PhpStorm.
 * User: Lixuan
 * Date: 2018/1/16
 * Time: 10:35
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
    	return $this->render('report', $params);
    }
}