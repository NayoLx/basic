<?php
/**
 * Created by PhpStorm.
 * User: Lixuan
 * Date: 2019/1/21
 * Time: 13:58
 */

namespace app\controllers;

use Yii;
use yii\db\Exception;
use yii\web\Controller;
use app\helpers\Utils;

class SettingController extends controller
{

    /**
     * @return string
     * tab选项卡框架
     */
    public function actionIndex()
    {
        return $this->render('main_setting');
    }

    //curl爬虫
    public function actionCurlsetting()
    {
        return $this->render('curl_setting');
    }

    //快递api
    public function actionKuaidisetting()
    {
        return $this->render('kuaidi_setting');
    }

    //百度api
    public function actionBaidusetting()
    {
        return $this->render('baidu_setting');
    }
}