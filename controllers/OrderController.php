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
       $formId = Yii::$app->request->post('formId', '');

       $stunumber = Yii::$app->db->createCommand('select stunumber from wxdeatil where openid = :openid')->bindValue(':openid', $openid)->queryOne();
       $phone = Yii::$app->db->createCommand('select phone from wxdeatil where openid = :openid')->bindValue(':openid', $openid)->queryOne();
       $stu_name = Yii::$app->db->createCommand('select stuname from user_student where stunumber = :stunumber')->bindValue(':stunumber', $stunumber['stunumber'])->queryOne();
       $order_no = Order::setOrder_no($order_type);
       $type = Order::setType($order_type);

       $time = date('y-m-d H:i:s',time());

       $check_account = Yii::$app->db->createCommand('select is_close from user_student where stunumber = :stunumber')->bindValue(':stunumber', $stunumber['stunumber'])->queryOne();

       if ($check_account['is_close'] == 'true') {
           $order->success = false;
           $order->error = '该账号已被封，请联系管理员';
           return json_encode($order);
       }

       $check = Yii::$app->db->createCommand( 'select * from order_detail where user_stunum = :stunumber and (status = :status or status = :sclose or status = :sclos)')
           ->bindValue(':stunumber', $stunumber['stunumber'])
           ->bindValue(':status', 1)
           ->bindValue(':sclose', 2)
           ->bindValue(':sclos', 3)
           ->queryAll();

       /*处于未完成状态的订单不能超过两个*/
       if (count($check) <= 2) {
           Yii::$app->db->createCommand()->insert('order_detail', [
               'order_no' => $order_no,
               'user_stunum' => $stunumber['stunumber'],
               'user_name' => $stu_name['stuname'],
               'user_phone' => $phone['phone'],
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

           if ($formId != 'the formId is a mock one') {
               self::setFormId($formId, $order_no);
           }

           //log日志
           $order -> order_id = $order_no;
           $order -> user_name =  $stu_name['stuname'];
           LogHelpers::orderLog(LogHelpers::ACTION_CREATE, $order);
           $order->success = true;
           $order->error = '';
           return json_encode($order);

       }
       else {
           $order->success = false;
           $order->error = '还有未处理的订单，暂无法下单';
           return json_encode($order);
       }
   }

    /**
     * @param $form_id
     * @param $order_no
     * @throws Exception
     * 保存formId
     */
   static function setFormId($form_id, $order_no)
   {
       $order_id = Yii::$app->db->createCommand('select id from order_detail where order_no = :order_no')->bindValue(':order_no', $order_no)->queryOne();
       $time = date('y-m-d H:i:s',time());

       Yii::$app->db->createCommand()->insert('order_form_id',[
           'order_id' => $order_id['id'],
           'formId' => $form_id,
           'creat_at' => $time,
           'updata_at' => $time,
       ])->execute();

   }

    /***
     * @return string
     * 访问微信接口获取access_token
     */
    public function actionWxtoken()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx1e5e51581c102b66&secret=b1cef0526d4c19b2261a0e33fee62e41';
        $ch = curl_init(); //初始化一个CURL对象
        curl_setopt($ch, CURLOPT_URL, $url);//设置你所需要抓取的URL
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//跳过证书验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置curl参数，要求结果是否输出到屏幕上，为true的时候是不返回到网页中,假设上面的0换成1的话，那么接下来的$data就需要echo一下。
        $data = json_decode(curl_exec($ch));

        return json_encode($data);
        curl_close($ch);

    }

   /**
    * 获取订单信息 （查
    */
   public function actionGetallorder()
   {
       $e = new \stdClass();
       $openid = Yii::$app->request->post('openid','');
       $status = Yii::$app->request->post('status', '');
       $stunumber = Yii::$app->db->createCommand('select stunumber from wxdeatil where openid = :openid')->bindValue(':openid', $openid)->queryOne();
       $check_close = Yii::$app->db->createCommand('select is_close from user_student where stunumber = :stunumber')->bindValue(':stunumber', $stunumber['stunumber'])->queryOne();

       if ($check_close['is_close'] == 'true' && $status != '10') {
           $e->error = '该账号已被封禁，无下单权限，请联系管理员';
           $e->success = false;
           return json_encode($e);
       }

       /*0--关于我的全部订单  1--我发起的订单  2--我接的单  10--附近发出的全部订单(除本人之外*/
       if ($status == '0') {
          $order = Yii::$app->db->createCommand('select * from order_detail where (user_stunum = :stunumber or staff_stunum = :stunum) and status < :status ')
              ->bindValue(':stunumber', $stunumber['stunumber'])
              ->bindValue(':stunum', $stunumber['stunumber'])
              ->bindValue(':status', '4')
              ->queryAll();

          $e->order = $order;
           $e->success = true;

          return json_encode($e);
       }
       else if ($status == '1') {
           $order = Yii::$app->db->createCommand('select * from order_detail where user_stunum = :stunumber  and status < :status ')
               ->bindValue(':stunumber', $stunumber['stunumber'])
               ->bindValue(':status', '4')
               ->queryAll();
           $e->order = $order;
           $e->success = true;

           return json_encode($e);
       }
       else if ($status == '2') {
           $order = Yii::$app->db->createCommand('select * from order_detail where staff_stunum = :stunum and status < :status ')
               ->bindValue(':stunum', $stunumber['stunumber'])
               ->bindValue(':status', '4')
               ->queryAll();
           $e->order = $order;
           $e->success = true;

           return json_encode($e);
       }
       else if($status == '10') {
           $order = Yii::$app->db->createCommand('select * from order_detail where user_stunum != :stunumber and  status = :status ')
               ->bindValue(':stunumber', $stunumber['stunumber'])
               ->bindValue(':status', '1')
               ->queryAll();
           $e->order = $order;
           $e->success = true;

           return json_encode($e);
       }
   }

   /**
    * 删除订单（伪），删除只是不在列表上显示，数据库还是存有数据
    */
   public function actionDeleteorder()
   {
       $order = new \stdClass();
       $e = new \stdClass();
       $e->success = false;
       $openid = Yii::$app->request->post('openid', '');
       $stunumber = Yii::$app->db->createCommand('select stunumber from wxdeatil where openid = :openid')->bindValue(':openid', $openid)->queryOne();
       $operator_id = Yii::$app->db->createCommand('select stuname from user_student where stunumber = :stunumber')->bindValue(':stunumber', $stunumber['stunumber'])->queryOne();

       $order_no = Yii::$app->request->post('order_no', '');
       $time = date('y-m-d H:i:s',time());

       $status = Yii::$app->db->createCommand('select status from order_detail where order_no = :order_no')->bindValue(':order_no', $order_no)->queryOne();

       if ($status['status'] == '3') {
           $e->error = '订单正在处理无法取消订单';
           return json_encode($e);
       }

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
          $e->success = true;
          return json_encode($e);
       }

       $e->error = '订单取消失败请重试';
       return json_encode($e);
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
       $phone = Yii::$app->db->createCommand('select phone from wxdeatil where openid = :openid')->bindValue(':openid', $openid)->queryOne();
       $stuname = Yii::$app->db->createCommand('select stuname from user_student where stunumber = :stunumber')->bindValue(':stunumber', $stunumber['stunumber'])->queryOne();
       $time = date('y-m-d H:i:s',time());

       /**********2--接单中 3--处理中 4--已完成********/

       if ($status == 2) {
           $check_close = Yii::$app->db->createCommand('select is_close from user_student where stunumber = :stunumber')->bindValue(':stunumber', $stunumber['stunumber'])->queryOne();

           if ($check_close['is_close'] == 'true') {
               $e->error = '该账号已被封禁，无接单权限，请联系管理员';
               return json_encode($e);
           }

           $check = Yii::$app->db->createCommand( 'select * from order_detail where staff_stunum = :stunumber and (status = :sclose or status = :sclos)')
               ->bindValue(':stunumber', $stunumber['stunumber'])
               ->bindValue(':sclose', 2)
               ->bindValue(':sclos', 3)
               ->queryAll();

           if (count($check)>=2) {
               $e->error = '还有未处理的订单，暂无法接单';
               return json_encode($e);
           }

           $e-> success = true;
           Yii::$app->db->createCommand()->update('order_detail', [
               'status' => '2',
               'staff_stunum' => $stunumber['stunumber'],
               'staff_name' => $stuname['stuname'],
               'staff_phone' => $phone['phone'],
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
       $parms = Yii::$app->db->createCommand('select * from order_detail order by id desc')->queryAll();
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
       $order['orderlist'] = Yii::$app->db->createCommand("select * from order_detail order by id desc")->queryAll();

       if(!empty($order_no)) {
           $order['orderlist'] = Yii::$app->db->createCommand("select * from order_detail where order_no LIKE :order_no order by id desc")->bindValue(':order_no', '%'.$order_no.'%')->queryAll();
       }
       if(!empty($user_name)) {
           $order['orderlist'] = Yii::$app->db->createCommand('select * from order_detail where user_name LIKE :user_name order by id desc')->bindValue(':user_name', '%'.$user_name.'%')->queryAll();
       }
       if(!empty($user_id)) {
           $order['orderlist'] = Yii::$app->db->createCommand('select * from order_detail where user_stunum LIKE :user_id order by id desc')->bindValue(':user_id', '%'.$user_id)->queryAll();
       }
       // if(!empty($user_phone)) {
       //     $order['orderlist'] = Yii::$app->db->createCommand('select * from order_detail where user_phone LIKE :user_phone')->bindValue(':user_phone', '%'.$user_phone.'%')->queryAll();
       // }
       if(!empty($staff_name)) {
           $order['orderlist'] = Yii::$app->db->createCommand('select * from order_detail where staff_name LIKE :staff_name order by id desc')->bindValue(':staff_name', '%'.$staff_name.'%')->queryAll();
       }
       if(!empty($staff_id)) {
           $order['orderlist']= Yii::$app->db->createCommand('select * from order_detail where staff_stunum LIKE :staff_id order by id desc')->bindValue(':staff_id', '%'.$staff_id.'%')->queryAll();
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
       $order_id = Yii::$app->request->post('order_id', '');
       $form_id = Yii::$app->request->post('form_id', '');
       $keyword1 = Yii::$app->request->post('keyword1', '');  //订单号
       $stu = Yii::$app->db->createCommand('select stunumber from wxdeatil where openid = :openid')->bindValue(':openid', $touser)->queryOne();
       $keyword2 = Yii::$app->db->createCommand('select stuname from user_student where stunumber = :stunumber')->bindValue(':stunumber', $stu['stunumber'])->queryOne(); //接单人名称
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

       $formId = Yii::$app->db->createCommand('select formId from order_form_id where order_id = :order_id')->bindValue(':order_id', $order_id)->queryOne();

       $dd['touser'] = $touser;
       $dd['template_id'] = $template_id;
       $dd['page'] = Yii::$app->request->post('page', '');;
       $dd['form_id'] = $formId['formId'];
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

    /**
     * @return string
     * @throws Exception
     *  分配订单 页面
     */
   public function actionOrderpai()
   {
       $id = Yii::$app->request->get('id', '');
       $params = [];
       $order = Yii::$app->db->createCommand('select * from order_detail where id = :id')->bindValue(':id', $id)->queryOne();
       $canpickarray = Yii::$app->db->createCommand('select * from user_student')->queryAll();
       $params['order'] = $order;
       $params['can_pick_array'] = $canpickarray;

       return $this->render('orderpai', $params);
   }

    /**
     * @return string
     * @throws Exception
     * 后台分配订单
     */
   public function actionOrderpick()
   {
       $e = new \stdClass();
       $e -> success = false;
       $order_no = Yii::$app->request->get('order_no', '');
       $staff_get = Yii::$app->request->get('staff', ' ');
       $staff = explode(' ', $staff_get);
       $time = date('y-m-d H:i:s',time());

       $check = Yii::$app->db->createCommand()->update('order_detail', [
           'staff_stunum' => $staff[0],
           'staff_name' => $staff[1],
           'status' => 2,
           'status_labal' => '接单中',
           'status_pick_time' => $time,
       ], 'order_no = :order_no')->bindValue(':order_no', $order_no)->execute();

       $e -> order_id = $order_no;
       $e -> staff_name = $staff[1];
       LogHelpers::orderLog(LogHelpers::ACTION_HT_PICK, $e);

       if($check == 1) {
           $e -> success = true;
           $e -> error = '保存成功';
           $e -> check = $check;
           return json_encode($e);
       }

       $e -> error = '出错啦';
       return json_encode($e);
   }

   /**
    * 后台关闭订单
    */
   public function actionHtcloseorder()
   {
       $e = new \stdClass();
       $orderid = Yii::$app->request->get('orderid', '');
       $id = Yii::$app->request->get('id', '');
       $time = date('y-m-d H:i:s',time());

       $check = Yii::$app->db->createCommand()->update('order_detail', [
           'status' => 6,
           'status_labal' => '后台人员关闭',
           'status_finish_time' => $time,
       ], 'id = :id')->bindValue(':id', $id)->execute();

       $e -> order_id = $orderid;
       $e -> staff_name = Yii::$app->session['username'];
       LogHelpers::orderLog(LogHelpers::ACTION_HT_CLOSE, $e);

       if($check == 1) {
           $e -> success = true;
           $e -> error = '保存成功';
           $e -> check = $check;
           return json_encode($e);
       }

       $e -> success = false;
       $e -> error = '出错啦';
       return json_encode($e);
   }


   /***
    * 后台重新打开订单
    */
   public function actionReopenorder()
   {
       $e = new \stdClass();
       $orderid = Yii::$app->request->get('orderid', '');
       $id = Yii::$app->request->get('id', '');
       $time = date('y-m-d H:i:s',time());

       $check = Yii::$app->db->createCommand()->update('order_detail', [
           'status' => 1,
           'status_labal' => '等待中',
           'status_reopen_time' => $time,
       ], 'id = :id')->bindValue(':id', $id)->execute();

       $e -> order_id = $orderid;
       $e -> staff_name = Yii::$app->session['username'];
       LogHelpers::orderLog(LogHelpers::ACTION_HT_REOPEN, $e);

       if($check == 1) {
           $e -> success = true;
           $e -> error = '保存成功';
           $e -> check = $check;
           return json_encode($e);
       }

       $e -> success = false;
       $e -> error = '出错啦';
       return json_encode($e);
   }

   /***
    * 测试用
    * 一键完成订单
    */
   public function actionOnekey()
   {
       $e = new \stdClass();
       $orderid = Yii::$app->request->get('orderid', '');
       $id = Yii::$app->request->get('id', '');
       $time = date('y-m-d H:i:s',time());

       $check = Yii::$app->db->createCommand()->update('order_detail', [
           'status' => 4,
           'status_labal' => '已完成',
           'status_finish_time' => $time,
           'is_finish' => 'true'
       ], 'id = :id')->bindValue(':id', $id)->execute();

       $e -> order_id = $orderid;
       $e -> staff_name = Yii::$app->session['username'];
       LogHelpers::orderLog(LogHelpers::ACTION_HT_FINISH, $e);

       if($check == 1) {
           $e -> success = true;
           $e -> error = '保存成功';
           $e -> check = $check;
           return json_encode($e);
       }

       $e -> success = false;
       $e -> error = '出错啦';
       return json_encode($e);
   }


   //备注
   public function actionRemark()
   {
       $e = new \stdClass();
       $orderid = Yii::$app->request->post('orderid', '');
       $id = Yii::$app->request->post('id', '');
       $remark =  Yii::$app->request->post('remark', '');
       $staff_name = Yii::$app->session['username'];

       $e->remark = $remark;
       $e->staff_name = $staff_name;

       return json_encode($e);
   }
}