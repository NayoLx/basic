<?php
/**
 * Created by PhpStorm.
 * User: Lixuan
 * Date: 2018/11/30
 * Time: 11:35
 */

namespace app\controllers;

use app\helpers\LogHelpers;
use Yii;
use yii\db\Exception;
use yii\web\Controller;
use app\helpers\Utils;
use app\controllers\MyController;

class UserController extends Controller
{
    /**
     * @return string
     * 页面类
     */
    public function actionIndex()
    {
        $user_list = $this->fuzzysearch();
        return $this->render('index', $user_list);
    }
    /**
     * 后台 订单编号模糊查询
     */
    public function fuzzysearch()
    {
        $request = \yii::$app->request;
        $user_id = $request->get('user_id', '');
        $user_name = $request->get('user_name', '');
        $user_phone = $request->get('user_phone', '');
        /**
         * 查询过滤处理
         */
        $order= [];
        $get = [];
        $get['user_id'] = empty($user_id) ? "" : $user_id;
        $get['user_name'] = empty($user_name) ? "" : $user_name;
        $get['phone'] = empty($user_phone) ? "" : $user_phone;


        $order['gets'] = $get;
        $order['user_list'] = Yii::$app->db->createCommand('select * from user_student')->queryAll();

        if(!empty($user_id)) {
            $order['user_list'] = Yii::$app->db->createCommand("select * from user_student where stunumber LIKE :user_no")->bindValue(':user_no', '%'.$user_id.'%')->queryAll();
        }
        if(!empty($user_name)) {
            $order['user_list'] = Yii::$app->db->createCommand('select * from user_student where stuname LIKE :user_name')->bindValue(':user_name', '%'.$user_name.'%')->queryAll();
        }
        // if(!empty($user_id)) {
        //     $order['user_list'] = Yii::$app->db->createCommand('select * from user_student where phone like :user_phone')->bindValue(':user_phone', '%'.$user_phone)->queryAll();
        // }


        return $order;

    }
    
    /*后台获取信息*/
    public function actionInfo() 
    {
        $request = \yii::$app->request;
        $stuid = intval($request->get('id'));
        $order_type = intval($request->get('order_type'));
        $year = intval($request->get('year'));
        $time = intval($request->get('time'));
        $params = array();
        $status = [];
        $scgedular = [];

        /**用户信息**/
        $user_info = Yii::$app->db->createCommand('select * from user_student where stunumber = :stunumber')->bindValue(':stunumber', $stuid)->queryOne();
        /**微信信息**/
        $user_avatar = Yii::$app->db->createCommand('select avatarUrl, phone, is_bind, is_close from wxdeatil where stunumber = :stunumber')->bindValue(':stunumber', $stuid)->queryOne();
        /**订单信息**/
        $orderlist = Yii::$app->db->createCommand("select * from order_detail where staff_stunum = :stunumber and (status = '2' or status = '3')")->bindValue(':stunumber', $stuid)->queryAll();
        /**课表*个人信息**/
        if($year == '' or $time == '') {
            $year = 2018;
            $time = 1;
        }
        $scgedular = $this->getScgedular($stuid, $year, $time);
        
        $params['info'] = $user_info;
        $params['avatar'] = $user_avatar;
        $status = ['status' => 0, 'year'=>$year, 'time'=> $time ];

        if ($order_type == 1) {
            $orderlist = Yii::$app->db->createCommand("select * from order_detail where status = '4' and staff_stunum = :stunumber")->bindValue(':stunumber', $stuid)->queryAll();
            $status['status'] =  1;
        }
        if ($order_type == 2) {
            $orderlist = Yii::$app->db->createCommand("select * from order_detail where  status = :status and user_stunum = :stunumber")
            ->bindValue(':status', 5)
            ->bindValue(':stunumber', $stuid)
            ->queryAll();
            $status['status'] = 2;
        }
        if ($order_type == 3) {
            $orderlist = Yii::$app->db->createCommand("select * from order_detail where user_stunum = :stunumber and status <> '5'")->bindValue(':stunumber', $stuid)->queryAll();
             $status['status'] = 3;
        }
        $params['gets'] = $status;
        $params['orderlist'] = $orderlist;
        $params['scgedular'] = $scgedular;
        $params['obligatory'] = unserialize($user_info['obligatory']);

        return $this->render('info', $params);
    }


    public function getScgedular($stunumber, $year, $time) 
    {
        
        $scgedular = Yii::$app->db->createCommand('select * from user_scgedular where stunumber = :stunumber and schoolyear = :year and semster = :time')
        ->bindValue(':stunumber', $stunumber)
        ->bindValue(':year', $year)
        ->bindValue(':time', $time)
        ->queryAll();
        
        if($scgedular == null && $scgedular == '' && $scgedular == []) {
            $password = Yii::$app->db->createCommand('select password from wxdeatil where stunumber = :stunumber')->bindValue(':stunumber', $stunumber)->queryOne();
            $check = MyController::moniLogin($stunumber, $password);
        }

        return $scgedular;
    }


    public function actionLogin()
    {
        if(Yii::$app->session['Login'] ==1) {
            return $this->goHome();
        }

        return $this->render('login');
    }

    public function actionDologin()
    {
        $e = new \stdClass();
        $e -> success = false;
        $username = Yii::$app->request->get('username', '');
        $password = Yii::$app->request->get('password', '');

        $has_user = Yii::$app->db->createCommand('select * from ht_user where username = :username')->bindValue(':username', $username)->queryOne();
        if($has_user == '' || $has_user == []) {
            $e -> error = '此账号无管理权限，请联系管理员';
            return json_encode($e);
        }
        $check = Yii::$app->db->createCommand('select password from ht_user where username = :username')->bindValue(':username', $username)->queryOne();
        if($password != $check['password']) {
            $e -> error = '账号密码输入错误，请重新输入';
            return json_encode($e);
        }
        $check_close = Yii::$app->db->createCommand('select is_close from ht_user where username = :username')->bindValue(':username', $username)->queryOne();
        if($check_close['is_close'] == 'true') {
            $e -> error = '该账号已禁用，请联系管理员';
            return json_encode($e);
        }

        Yii::$app->session['be_login'] = 1;
        Yii::$app->session['username'] = $username;
        LogHelpers::loginLog(1);     //登陆log
        $e -> success = true;
        $e -> error = '登陆成功';

        return $this->goHome();
    }

    public function actionLogout()
    {
        LogHelpers::loginLog(0);     //登陆log
        Yii::$app->session->remove('be_login');
        Yii::$app->session->remove('username');

        return $this->render('login');
    }

    public function actionOpenaccount()
    {
        $e = new \stdClass();
        $request = \yii::$app->request;
        $stuid = intval($request->get('id'));

        Yii::$app->db->createCommand()->update('user_student', [
            'is_close' => 'false',
        ], 'stunumber = :id')->bindValue(':id', $stuid)->execute();

        $e->success = true;

        return json_encode($e);
    }

    public function actionCloseaccount()
    {
        $e = new \stdClass();
        $request = \yii::$app->request;
        $stuid = intval($request->get('id'));

        Yii::$app->db->createCommand()->update('user_student', [
            'is_close' => 'true',
        ], 'stunumber = :id')->bindValue(':id', $stuid)->execute();

        $e->success = true;

        return json_encode($e);
    }
}