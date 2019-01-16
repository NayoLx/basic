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
use app\helpers\LogHelpers;

class OrderController extends Controller
{
    /**
     * @return string
     * 页面类
     */
    public function actionOrderlist()
    {
        $order = $this->fuzzysearch();
        return $this->render('orderList', $order);
    }

    public function actionOrderdetail()
    {
        $request = \yii::$app->request;
        $id = intval($request->get('id'));
        $params = array();

        $order_data = Yii::$app->db->createCommand('select * from order_detail where id = :id')->bindValue(':id', $id)->queryOne();
        $order_log = Yii::$app->db->createCommand('select * from order_log where order_no = :order_no')->bindValue(':order_no', $order_data['order_no'])->queryAll();
        $staff_phone = Yii::$app->db->createCommand('select phone from wxdeatil where stunumber = :stunumber')->bindValue(':stunumber', $order_data['staff_stunum'])->queryOne();

        $params['data'] = $order_data;
        $params['order_log'] = $order_log;
        $params['staff_phone'] = $staff_phone;


        return $this->render('orderdetail', $params);
    }

    /**
     * 保存订单详情 （增
     */
   public function actionSaveorderdetail()
   {
       $order = new \stdClass();
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

       $check = Yii::$app->db->createCommand( 'select * from order_detail where user_stunum = :stunumber and status <> :status')
           ->bindValue(':stunumber', $stunumber['stunumber'])
           ->bindValue(':status', 5)
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

           //log日志
           $order -> order_id = $order_no;
           $order -> user_name =  $stu_name['stuname'];
           LogHelpers::orderLog(LogHelpers::ACTION_CREATE, $order);

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
       $order = new \stdClass();
       $openid = Yii::$app->request->post('openid', '');
       $stunumber = Yii::$app->db->createCommand('select stunumber from wxdeatil where openid = :openid')->bindValue(':openid', $openid)->queryOne();
       $operator_id = Yii::$app->db->createCommand('select stuname from student where stunumber = :stunumber')->bindValue(':stunumber', $stunumber['stunumber'])->queryOne();

       $order_no = Yii::$app->request->post('order_no', '');
       $time = date('y-m-d H:i:s',time());

       Yii::$app->db->createCommand()->update('order_detail', [
           'status' => '5',
           'status_labal' => '已删除',
           'status_delete_time' => $time,
           'is_delete' => 'true',
       ], 'order_no = :order_no')->bindValue(':order_no', $order_no)->execute();

       $check = Yii::$app->db->createCommand('select is_delete from order_detail where order_no = :order_no')->bindValue(':order_no', $order_no)->queryOne();

       if ($check['is_delete'] == 'true') {
          //log日志
          $order -> user_name = $operator_id['stuname'];
          $order -> order_id = $order_no;

          LogHelpers::orderlog(LogHelpers::ACTION_DELETE, $order);
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
       $e -> success = false;
       $order_no = Yii::$app->request->post('order_no', '');
       $openid = Yii::$app->request->post('openid', '');
       $status = Yii::$app->request->post('status', '');

       /***
        * 该用户
        */
       $stunumber = Yii::$app->db->createCommand('select stunumber from wxdeatil where openid = :openid')->bindValue(':openid', $openid)->queryOne();
       $stuname = Yii::$app->db->createCommand('select stuname from student where stunumber = :stunumber')->bindValue(':stunumber', $stunumber['stunumber'])->queryOne();
       $time = date('y-m-d H:i:s',time());

       /**********2--接单中 3--处理中 4--已完成********/

       if ($status == 2) {
           $e-> success = true;
           Yii::$app->db->createCommand()->update('order_detail', [
               'status' => '2',
               'staff_stunum' => $stunumber['stunumber'],
               'staff_name' => $stuname['stuname'],
               'status_labal' => '接单中',
               'status_pick_time' => $time,
               'is_bind_take' =>'true',
           ], 'order_no = :order_no')->bindValue(':order_no', $order_no)->execute();

           $e -> order_id = $order_no;
           $e -> user_name = $stuname['stuname'];
           LogHelpers::orderlog(LogHelpers::ACTION_PICK, $e);

           return json_encode($e);
       }
       elseif ($status == 3) {
           $e-> success = true;
           Yii::$app->db->createCommand()->update('order_detail', [
               'status' => '3',
               'status_labal' => '处理中',
               'status_doing_time' => $time,
               'is_bind_done' =>'true',
           ], 'order_no = :order_no')->bindValue(':order_no', $order_no)->execute();

           $e -> order_id = $order_no;
           $e -> user_name = $stuname['stuname'];
           LogHelpers::orderlog(LogHelpers::ACTION_DOING, $e);

           return json_encode($e);
       }
       elseif ($status == 4) {
           $e-> success = true;
           Yii::$app->db->createCommand()->update('order_detail', [
               'status' => '4',
               'status_labal' => '已完成',
               'status_finish_time' => $time,
               'is_finish' =>'true',
           ], 'order_no = :order_no')->bindValue(':order_no', $order_no)->execute();
           
           $e -> order_id = $order_no;
           $e -> user_name = $stuname['stuname'];
           LogHelpers::orderlog(LogHelpers::ACTION_FINISE, $e);

           return json_encode($e);
       }


       return $e;

   }


   /**
    * 后台 获取所有订单
    */
   public function actionOrderall()
   {
       $parms = Yii::$app->db->createCommand('select * from order_detail')->queryAll();
      return $parms;
   }

    /**
     * 后台 订单编号模糊查询
     */
   public function fuzzysearch()
   {
       $request = \yii::$app->request;
       $order_no = $request->get('order_no');
       $user_name = $request->get('user_name');
       $user_id = $request->get('user_id');
       $user_phone = $request->get('user_phone');
       $staff_name = $request->get('staff_name');
       $staff_id = $request->get('staff_id');
       /**
        * 查询过滤处理
        */
       $order= [];
       $get = [];
       $get['order_no'] = empty($order_no) ? "" : $order_no;
       $get['user_name'] = empty($user_name) ? "" : $user_name;
       $get['user_id'] = empty($user_id) ? "" : $user_id;
       $get['user_phone'] = empty($user_phone) ? "" : $user_phone;
       $get['staff_name'] = empty($staff_name) ? "" : $staff_name;
       $get['staff_id'] = empty($staff_id) ? "" : $staff_id;

       $order['gets'] = $get;
       $order['orderlist'] = Yii::$app->db->createCommand("select * from order_detail ")->queryAll();

       if(!empty($order_no)) {
           $order['orderlist'] = Yii::$app->db->createCommand("select * from order_detail where order_no LIKE :order_no")->bindValue(':order_no', '%'.$order_no.'%')->queryAll();
       }
       if(!empty($user_name)) {
           $order['orderlist'] = Yii::$app->db->createCommand('select * from order_detail where user_name like :user_name')->bindValue(':user_name', '%'.$user_name.'%')->queryAll();
       }
       if(!empty($user_id)) {
           $order['orderlist'] = Yii::$app->db->createCommand('select * from order_detail where user_id like :user_id')->bindValue(':user_id', '%'.$user_id.'%')->queryAll();
       }
       if(!empty($user_phone)) {
           $order['orderlist'] = Yii::$app->db->createCommand('select * from order_detail where user_phone like :user_phone')->bindValue(':user_phone', '%'.$user_phone.'%')->queryAll();
       }
       if(!empty($staff_name)) {
           $order['orderlist'] = Yii::$app->db->createCommand('select * from order_detail where staff_name like :staff_name')->bindValue(':staff_name', '%'.$staff_name.'%')->queryAll();
       }
       if(!empty($staff_id)) {
           $order['orderlist']= Yii::$app->db->createCommand('select * from order_detail where staff_id like :staff_id')->bindValue(':staff_id', '%'.$staff_id.'%')->queryAll();
       }

       return $order;

   }



   /**
    * @模板消息
    * 微信Api的获取与数据获取
    */
   public function actionGetwxapi()
   {
       $e = new \stdClass();
       $e -> success = false;

       $touser = Yii::$app->request->post('touser', '');
       $template_id = Yii::$app->request->post('template_id', '');
       $form_id = Yii::$app->request->post('form_id', '');
       $keyword1 = Yii::$app->request->post('keyword1', '');  //订单号
       $stu = Yii::$app->db->createCommand('select stunumber from wxdeatil where openid = :openid')->bindValue(':openid', $touser)->queryOne();
       $keyword2 = Yii::$app->db->createCommand('select stuname from student where stunumber = :stunumber')->bindValue(':stunumber', $stu['stunumber'])->queryOne(); //接单人名称
       $keyword3 = date('y-m-d H:i:s',time()); //接单时间
       $keyword4 = Yii::$app->db->createCommand('select phone from wxdeatil where openid = :openid')->bindValue(':openid', $touser)->queryOne(); //电话
       $keyword5 = ''; //微信号
       $keyword6 = Yii::$app->request->post('keyword6', '');  //下单时间
       $keyword7 = Yii::$app->request->post('keyword7', '');  //客户名称
       $keyword8 = ''; //客户电话
       $access_token = Yii::$app->request->post('access_token', '');

       $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=' . $access_token;

//       return json_encode('https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='. $access_token);
       $value = array(
           'keyword1'=>array(
             'value' => $keyword1,
           ),
           'keyword2'=>array(
               'value' => $keyword2['stuname'],
           ),
           'keyword3'=>array(
               'value' => $keyword3,
           ),
           'keyword4'=>array(
               'value' => $keyword4['phone'],
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
       $data = array();

       $dd['touser'] = $touser;
       $dd['template_id'] = $template_id;
       $dd['page'] = Yii::$app->request->post('page', '');;
       $dd['form_id'] = $form_id;
       $dd['data'] = $value;

//       return json_encode($dd);
       $result = utils::https_curl_json($url, $dd, 'json');

       if($result){
           echo json_encode(array('state'=>5,'msg'=>$result));
       }else{
           echo json_encode(array('state'=>5,'msg'=>$result));
       }

//       $user_stunum =  Yii::$app->request->post('user_stunum', '');
//       $userid = Yii::$app->db->createCommand('select openid from wxdeatil where stunumber = :stunumber')->bindValue(':stunumber', $user_stunum)->queryOne();
//
//       $data['touser'] = $userid['openid'];
//
//       if ($data['touser'] == '') {
//           return json_encode($e);
//       }
//       $data['template_id'] = $template_id;
//       $data['page'] = Yii::$app->request->post('page', '');;
//       $data['form_id'] = $form_id;
//       $data['data'] = $value;
//
//       self::sendMessageUser($data, $url);

   }


   /**
    * @模板消息
    *
    */
   public function sendMessageUser($data, $url)
   {
       $result = utils::https_curl_json($url, $data, 'json');

//       if($result){
//           echo json_encode(array('state'=>5,'msg'=>$result));
//       }else{
//           echo json_encode(array('state'=>5,'msg'=>$result));
//       }
   }
}