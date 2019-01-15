<?php
/**
 * Created by PhpStorm.
 * User: lixuan
 * Date: 2019/1/14
 * Time: 9:38
 */
namespace app\helpers;

use Yii;

class LogHelpers
{
    const ACTION_CREATE = 1; // 订单创建
    const ACTION_PICK = 2; // 接单
    const ACTION_DOING = 3; // 处理中
    const ACTION_FINISE = 4; // 完成订单
    const ACTION_PICK_CLOSE = 5; //接单人关闭订单
    const ACTION_USER_CLOSE = 6; //用户关闭订单
    const ACTION_DELETE = 7; //用户删除订单


    // 操作员类型：0-用户;1-接单人;2-后台管理员; 3-系统
    const OPERATOR_TYPE_USER = 0;
    const OPERATOR_TYPE_ENGINEER = 1;
    const OPERATOR_TYPE_ADMIN = 2;
    const OPERATOR_TYPE_SYSTEM = 3;

    static function orderLog($action, $order)
    {
    	if ($order == '') {
    		return flase;
    	}

    	$content = '';
    	$order_id = 0;
    	$operator_id = 0;
    	$operator_type = LogHelpers::OPERATOR_TYPE_USER;

    	if (self::ACTION_CREATE == $action) {
    		$content = '客户创建订单';
    		$order_id = $order -> order_id;
    		$operator_id = $order -> user_name;
    		$operator_type = LogHelpers::OPERATOR_TYPE_USER;
    	} elseif (self::ACTION_PICK == $action) {
    		$content = '有用户接单';
    		$order_id = $order -> order_id;
    		$operator_id = $order -> user_name;
    		$operator_type = LogHelpers::OPERATOR_TYPE_ENGINEER;
    	} elseif (self::ACTION_DOING == $action) {
    		$content = '用户处理中';
    		$order_id = $order -> order_id;
    		$operator_id = $order -> user_name;
    		$operator_type = LogHelpers::OPERATOR_TYPE_ENGINEER;
    	} elseif (self::ACTION_FINISE == $action) {
    		$content = '完成订单';
    		$order_id = $order -> order_id;
    		$operator_id = $order -> user_name;
    		$operator_type = LogHelpers::OPERATOR_TYPE_USER;
    	} elseif (self::ACTION_USER_CLOSE == $action) {
    		$content = '客户关闭订单';
    		$order_id = $order -> order_id;
    		$operator_id = $order -> user_name;
    		$operator_type = LogHelpers::OPERATOR_TYPE_USER;
    	} elseif (self::ACTION_PICK_CLOSE == $action) {
    		$content = '接单人关闭订单';
    		$order_id = $order -> order_id;
    		$operator_id = $order -> staff_name;
    		$operator_type = LogHelpers::OPERATOR_TYPE_ENGINEER;
    	} elseif (self::ACTION_DELETE == $action) {
    		$content = '客户删除订单';
    		$order_id = $order -> order_id;
    		$operator_id = $order -> user_name;
    		$operator_type = LogHelpers::OPERATOR_TYPE_USER;
    	} elseif (self::ACTION_DELETE == $action) {
    		$content = '接单人删除订单';
    		$order_id = $order -> order_id;
    		$operator_id = $order -> user_name;
    		$operator_type = LogHelpers::OPERATOR_TYPE_ENGINEER;
    	} else {
    		return false ;
    	}
        $time = date('y-m-d H:i:s',time());

        Yii::$app->db->createCommand()->insert('order_log', [
               'order_no' => $order_id,
               'log_master' => $operator_id,
               'log_type' => $operator_type,
               'log_message' => $content,
               'log_time' => $time,
           ])->execute();
    	
    	return true;
    }
}