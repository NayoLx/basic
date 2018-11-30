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
use app\helpers\Utils;

class OrderController extends Controller
{
    /**
     * @return string
     * 页面类
     */
    public function actionOrderlist()
    {
        return $this->render('orderList');
    }
    public function actionOrderdetail()
    {
        return $this->render('orderdetail');
    }

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
       $stu_name = Yii::$app->db->createCommand('select stuname from student where stunumber = :stunumber')->bindValue(':stunumber', $stunumber['stunumber'])->queryOne();
       $order_no = Order::setOrder_no($order_type);
       $type = Order::setType($order_type);

       $time = date('y-m-d H:i:s',time());

       $check = Yii::$app->db->createCommand( 'select * from order_detail where user_stunum = :stunumber and status != :status')
           ->bindValue(':stunumber', $stunumber['stunumber'])
           ->bindValue(':status', '4')
           ->queryAll();

       /*处于未完成状态的订单不能超过两个*/
       if (count($check) <= 2) {
           Yii::$app->db->createCommand()->insert('order_detail', [
               'order_no' => $order_no,
               'user_stunum' => $stunumber['stunumber'],
               'user_name' => $stu_name['stuname'],
               'order_type' => $order_type,
               'type' => $type,
               'sex' => $sex,
               'contact_building' => $address,
               'express_detail_text' => $detail_text,
               'express_detail_starttime' => $start_time,
               'express_detail_endtime' => $end_time,
               'status_wait_time' => $time,
               'push_time' => $time,
               'status' => '1',
               'status_labal' => '等待中',
               'is_bind_take' => 'false',
               'is_bind_done' => 'false',
               'is_finish' => 'false',
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

       /*0--关于我的全部订单  1--我发起的订单  2--我接的单  10--附近发出的全部订单(除本人之外*/
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
           $order = Yii::$app->db->createCommand('select * from order_detail where user_stunum != :stunumber and  status = :status ')
               ->bindValue(':stunumber', $stunumber['stunumber'])
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

       echo $order_no;

       if ($check['is_delete'] == 'true') {
           return 'true';
       }

       return 'false';
   }

   /**
    * 更改订单状态(改
    */
   public function actionChangeorder()
   {
       $e = new \stdClass();
       $order_no = Yii::$app->request->post('order_no', '');
       $openid = Yii::$app->request->post('openid', '');
       $status = Yii::$app->request->post('status', '');

       /***
        * 该用户
        */
       $stunumber = Yii::$app->db->createCommand('select stunumber from wxdeatil where openid = :openid')->bindValue(':openid', $openid)->queryOne();
       $stuname = Yii::$app->db->createCommand('select stuname from student where stunumber = :stunumber')->bindValue(':stunumber', $stunumber['stunumber'])->queryOne();
       $time = date('y-m-d H:i:s',time());

       echo $time;
       /**********2--接单中 3--处理中 4--已完成********/

       if ($status == 2) {
           $e->success = true;
           Yii::$app->db->createCommand()->update('order_detail', [
               'status' => '2',
               'staff_stunum' => $stunumber['stunumber'],
               'staff_name' => $stuname['stuname'],
               'status_labal' => '接单中',
               'status_pick_time' => $time,
               'is_bind_take' =>'true',
           ], 'order_no = :order_no')->bindValue(':order_no', $order_no)->execute();

           return json_encode($e);
       }
       elseif ($status == 3) {
           $e->success = true;
           Yii::$app->db->createCommand()->update('order_detail', [
               'status' => '3',
               'status_labal' => '处理中',
               'status_doing_time' => $time,
               'is_bind_done' =>'true',
           ], 'order_no = :order_no')->bindValue(':order_no', $order_no)->execute();

           return json_encode($e);
       }
       elseif ($status == 4) {
           $e->success = true;
           Yii::$app->db->createCommand()->update('order_detail', [
               'status' => '4',
               'status_labal' => '已完成',
               'status_finish_time' => $time,
               'is_finish' =>'true',
           ], 'order_no = :order_no')->bindValue(':order_no', $order_no)->execute();

           return json_encode($e);
       }

       $e->success = false;
       return $e;

   }


   /**
    * 后台 获取所有订单
    */
   public function actionOrderall()
   {
      $e = new \stdClass();
      $e -> orderlist = Yii::$app->db->createCommand('select * from order_detail')->queryAll();

      return json_encode($e);
   }

    /**
     * 后台 订单编号模糊查询
     */
   public function actionOrderfuzzysearch()
   {
       $e = new \stdClass();
       $order_val = Yii::$app->request->post('order_val', '');

       if ($order_val != '') {
           $e -> orderlist = Yii::$app->db->createCommand(" select * from order_detail where order_no LIKE :order_val ")->bindValue(':order_val', '%'.$order_val)->queryAll();
       }
       else {
           $e -> orderlist = Yii::$app->db->createCommand('select * from order_detail')->queryAll();
       }

       return json_encode($e);
   }

   /***
    * 微信Api的获取与数据获取
    */
   public function actionGetwxapi()
   {
       $e = new \stdClass();

       $e -> touser = Yii::$app->request->post('touser', '');
       $e -> template_id = Yii::$app->request->post('template_id', '');
       $e -> form_id = Yii::$app->request->post('form_id', '');
       $keyword = Yii::$app->request->post('keyword1', '');
       $keyword2 = Yii::$app->request->post('keyword2', '');
       $keyword3 = Yii::$app->request->post('keyword3', '');
       $keyword4 = Yii::$app->request->post('keyword4', '');
       $keyword5 = Yii::$app->request->post('keyword5', '');
       $keyword6 = Yii::$app->request->post('keyword6', '');
       $keyword7 = Yii::$app->request->post('keyword7', '');
       $keyword8 = Yii::$app->request->post('keyword8', '');
       $access_token = Yii::$app->request->post('access_token', '');

       $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=' + $access_token;

       return json_encode($e);
       $value = array(
           'keyword1'=>array(
             'value' => $keyword,
           ),
           'keyword2'=>array(
               'value' => $keyword2,
           ),
           'keyword3'=>array(
               'value' => $keyword3,
           ),
           'keyword4'=>array(
               'value' => $keyword4,
           ),
           'keyword5'=>array(
               'value' => $keyword5,
           ),
           'keyword6'=>array(
               'value' => $keyword6,
           ),
           'keyword7'=>array(
               'value' => $keyword7,
           ),
           'keyword8'=>array(
               'value' => $keyword8,
           )
       );


       $dd = array();

       $dd['touser'] = $touser;
       $dd['template_id'] = $template_id;
       $dd['page'] = '';
       $dd['form_id'] = $form_id;

       $dd['value'] = $value;

       $dd['color']='';
       $dd['emphasis_keyword']='';


       $result = utils::https_curl_json($url, $dd, 'json');

       if($result){
           echo json_encode(array('state'=>5,'msg'=>$result));
       }else{
           echo json_encode(array('state'=>5,'msg'=>$result));
       }
   }


}