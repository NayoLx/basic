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
       return $this->render('index');
    }
    public function actionReport()
    {
    	return $this->render('report');
    }
}