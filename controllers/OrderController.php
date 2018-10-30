<?php
/**
 * Created by PhpStorm.
 * User: lixuan
 * Date: 2018/10/23
 * Time: 14:52
 */
namespace app\controllers;

use Yii;
use yii\db\Exception;
use yii\web\Controller;
use app\helpers\Order;

class OrderController extends Controller
{
    /**
     * 保存订单详情 （增
     */
   public function actionSaveorderdetail()
   {
       $openid = Yii::$app->request->post('openid','');
       $order_type = Yii::$app->request->post('order_type','');
       $sex = Yii::$app->request->post('sex', '');
       $address = Yii::$app->request->post('address', '');
       $detail_text = Yii::$app->request->post('detail_text', '');
       $start_time = Yii::$app->request->post('start_time', '');
       $end_time = Yii::$app->request->post('end_time', '');

       $stunumber = Yii::$app->db->createCommand('select stunumber from wxdeatil where openid = :openid')->bindValue(':openid', $openid)->queryOne();
       $order_no = Order::setOrder_no($order_type);
       $type = Order::setType($order_type);

       $time = date('y-m-d H:i:s',time());

       $check = Yii::$app->db->createCommand( 'select * from order_detail where status != :status')->bindValue(':status', '4')->queryAll();

       /*处于未完成状态的订单不能超过两个*/
       if (count($check) <= 2) {
           Yii::$app->db->createCommand()->insert('order_detail', [
               'order_no' => $order_no,
               'user_stunum' => $stunumber['stunumber'],
               'type' => $type,
               'sex' => $sex,
               'contact_building' => $address,
               'express_detail_text' => $detail_text,
               'express_detail_starttime' => $start_time,
               'express_detail_endtime' => $end_time,
               'status_wait_time' => $time,
               'status' => '1',
               'status_labal' => '等待中',
           ])->execute();
           return 'true';
       }
       else {
           return 'false';
       }
   }

   /**
    * 获取订单信息 （查
    */
   public function actionGetallorder()
   {
       $openid = Yii::$app->request->post('openid','');
       $status = Yii::$app->request->post('status', '');
       $stunumber = Yii::$app->db->createCommand('select stunumber from wxdeatil where openid = :openid')->bindValue(':openid', $openid)->queryOne();

       /*0--关于我的全部订单  1--我发起的订单  2--我接的单  10--附近发出的全部订单*/
       if ($status == '0') {
          $order = Yii::$app->db->createCommand('select * from order_detail where (user_stunum = :stunumber or staff_stunum = :stunum) and status < :status ')
              ->bindValue(':stunumber', $stunumber['stunumber'])
              ->bindValue(':stunum', $stunumber['stunumber'])
              ->bindValue(':status', '4')
              ->queryAll();

          return json_encode($order);
       }
       else if ($status == '1') {
           $order = Yii::$app->db->createCommand('select * from order_detail where user_stunum = :stunumber  and status < :status ')
               ->bindValue(':stunumber', $stunumber['stunumber'])
               ->bindValue(':status', '4')
               ->queryAll();

           return json_encode($order);
       }
       else if ($status == '2') {
           $order = Yii::$app->db->createCommand('select * from order_detail where staff_stunum = :stunum and status < :status ')
               ->bindValue(':stunum', $stunumber['stunumber'])
               ->bindValue(':status', '4')
               ->queryAll();

           return json_encode($order);
       }
       else if($status == '10') {
           $order = Yii::$app->db->createCommand('select * from order_detail where  status = :status ')
               ->bindValue(':status', '1')
               ->queryAll();

           return json_encode($order);
       }
   }

   /**
    * 删除订单（伪），删除只是不在列表上显示，数据库还是存有数据
    */
   public function actionDeleteorder()
   {
       $openid = Yii::$app->request->post('openid', '');
       $order_no = Yii::$app->request->post('order_no', '');

       Yii::$app->db->createCommand()->update('order_detail', [
           'status' => '5',
           'status_labal' => '已删除',
           'is_delete' => 'true',
       ], 'order_no = :order_no')->bindValue(':order_no', $order_no)->execute();

       $check = Yii::$app->db->createCommand('select is_delete from order_detail where order_no = :order_no')->bindValue(':order_no', $order_no)->queryOne();

       if ($check['is_delete'] == 'true') {
           return 'true';
       }
   }

   /**
    * 更改订单状态(改
    */
   public function actionChangeorder()
   {
       $order_no = Yii::$app->request->post('order_no', '');

   }
}