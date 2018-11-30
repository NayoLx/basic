<?php
/**
 * Created by PhpStorm.
 * User: Lixuan
 * Date: 2018/11/30
 * Time: 11:35
 */

namespace app\controllers;

use Yii;
use yii\db\Exception;
use yii\web\Controller;
use app\helpers\Utils;

class UserController extends Controller
{
    /**
     * @return string
     * 页面类
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 后台 用户列表
     */
    public function actionUserlist()
    {
        $e = new \stdClass();

        $e -> userlist = Yii::$app->db->createCommand('select * from student')->queryAll();

        return json_encode($e);
    }
}