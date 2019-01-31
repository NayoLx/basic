<?php
/**
 * Created by PhpStorm.
 * User: Lixuan
 * Date: 2019/1/18
 * Time: 15:58
 */

namespace app\controllers;

use Yii;
use yii\db\Exception;
use yii\web\Controller;
use app\helpers\Utils;

class SystemController extends Controller
{

    /**
     * @return string
     * **admin_sys页面
     */
    public function actionIndex()
    {
        $params = [];

        $admin_list = Yii::$app->db->createCommand('select * from ht_user')->queryAll();
        $params['admin_list'] = $admin_list;

       return $this->render('admin_sys', $params);
    }

    /**
     * @return string
     * **admin setting and create
     */
    public function actionAdmincreate()
    {
        return $this->render('create');
    }

    public function actionSubmitcreate()
    {
        $e = new \stdClass();
        $e -> success = false;
        $username = Yii::$app->request->get('username', '');
        $password = Yii::$app->request->get('password', '');
        $password_c = Yii::$app->request->get('password_confirm', '');
        $realname = Yii::$app->request->get('realname', '');
        $email = Yii::$app->request->get('email', '');
        $phone = Yii::$app->request->get('mobile', '');

        if ($username == '') {
            $e -> error = '用户名为空，请输入用户名！';
            return json_encode($e);
        }
        if ($password == '') {
            $e -> error = '密码为空，请输入密码！';
            return json_encode($e);
        }
        if ($password_c != $password) {
        	$e -> error = '两次密码输入不同，请重新输入！';
            return json_encode($e);
        }
        if ($realname == '') {
            $e -> error = '您的名字为空，请输入！';
            return json_encode($e);
        }
        if ($email == '') {
            $e -> error = '邮箱不能为空，请输入！';
            return json_encode($e);
        }
        if ($phone == '') {
            $e -> error = '手机号码不能为空，请输入！';
            return json_encode($e);
        }

        $check = Yii::$app->db->createCommand('select * from ht_user where username = :username')->bindValue(':username', $username)->queryOne();

        if (!$check) {
            $e -> success = true;
            Yii::$app->db->createCommand()->insert('ht_user',[
                'username' => $username,
                'password' => $password,
                'role' => '管理员',
                'name' => $realname,
                'e-mail' => $email,
                'phone' => $phone,
                'is_close' => 'false',
            ])->execute();
        }

        return json_encode($e);
    }

    public function actionSubmitedit()
    {
        $e = new \stdClass();
        $e -> success = false;
        $username = Yii::$app->request->get('username', '');
        $realname = Yii::$app->request->get('realname', '');
        $email = Yii::$app->request->get('email', '');
        $phone = Yii::$app->request->get('mobile', '');
        $is_close = Yii::$app->request->get('is_close', '');

        if ($username == '') {
            $e -> error = '用户名为空，请输入用户名！';
            return json_encode($e);
        }
        if ($realname == '') {
            $e -> error = '您的名字为空，请输入！';
            return json_encode($e);
        }
        if ($email == '') {
            $e -> error = '邮箱不能为空，请输入！';
            return json_encode($e);
        }
        if ($phone == '') {
            $e -> error = '手机号码不能为空，请输入！';
            return json_encode($e);
        }

        $check = Yii::$app->db->createCommand('select * from ht_user where username = :username')->bindValue(':username', $username)->queryOne();

        if (!$check) {
            $e -> success = true;
            Yii::$app->db->createCommand()->update('ht_user',[
                'username' => $username,
                'name' => $realname,
                'e-mail' => $email,
                'phone' => $phone,
                'is_close' => 'false',
            ])->execute();
        }

        return json_encode($e);
    }

    public function actionAdminsetting()
    {
        $params = [];
        $id = Yii::$app->request->get('id', '');
        $user = Yii::$app->db->createCommand('select * from ht_user where id = :id')->bindValue(':id', $id)->queryOne();

        $params['user'] = $user;

        return $this->render('edit_admin', $params);
    }

    /***
     * @return string
     * **角色类型
     */
    public function actionRoleindex()
    {
        $params = [];

        return $this->render('role', $params);
    }

    /***
     * @return string
     * **role create
     */
    public function actionRolecreate()
    {

    }
}