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
        $params = [];
        $array_url = Yii::$app->db->createCommand('select * from api_curl where id = :id')->bindValue(':id', '1')->queryOne();
        $params['array_url'] = $array_url;

        return $this->render('curl_setting', $params);
    }

    public function actionCurlsave()
    {
        $e = new \stdClass();
        $ip = Yii::$app->request->post('ip','');
        $url = Yii::$app->request->post('url','');
        $loginurl = Yii::$app->request->post('loginurl','');
        $schedualrurl = Yii::$app->request->post('schedualrurl','');
        $indexurl = Yii::$app->request->post('indexurl','');


        Yii::$app->db->createCommand()->update('api_curl',[
            'ip' => $ip,
            'url' => $url,
            'loginUrl' => $loginurl,
            'schedularUrl' => $schedualrurl,
            'indexUrl' => $indexurl,
        ], 'id = :id')->bindValue(':id', '1')->execute();

        $e -> success = true;
        $e -> title = '保存成功';
        return json_encode($e);
    }

    //快递api
    public function actionKuaidisetting()
    {
        $params = [];
        $array_api = Yii::$app->db->createCommand('select * from api_kuaidi where id = :id')->bindValue(':id', '1')->queryOne();
        $params['array_api'] = $array_api;

        return $this->render('kuaidi_setting', $params);
    }

    public function actionKuaidisave()
    {
        $e = new \stdClass();
        $EBusinessID = Yii::$app->request->post('EBusinessID','');
        $appkey = Yii::$app->request->post('appkey','');
        $requrl = Yii::$app->request->post('requrl','');
        $kuaidi_url = Yii::$app->request->post('kuaidi_url','');


        Yii::$app->db->createCommand()->update('api_kuaidi',[
            'EBusinessID' => $EBusinessID,
            'AppKey' => $appkey,
            'ReqURL' => $requrl,
            'kuaidi_url' => $kuaidi_url,
        ], 'id = :id')->bindValue(':id', '1')->execute();

        $e -> success = true;
        $e -> title = '保存成功';
        return json_encode($e);
    }

    //百度api
    public function actionBaidusetting()
    {
        $params = [];
        $array_api = Yii::$app->db->createCommand('select * from api_baidu where id = :id')->bindValue(':id', '1')->queryOne();
        $params['array_api'] = $array_api;

        return $this->render('baidu_setting', $params);
    }

    public function actionBaidusave()
    {
        $e = new \stdClass();
        $appid = Yii::$app->request->post('appid','');
        $apikey = Yii::$app->request->post('apikey','');
        $sckey = Yii::$app->request->post('sckey','');


        Yii::$app->db->createCommand()->update('api_baidu',[
            'AppID' => $appid,
            'API Key' => $apikey,
            'Secret Key' => $sckey,
        ], 'id = :id')->bindValue(':id', '1')->execute();

        $e -> success = true;
        $e -> title = '保存成功';
        return json_encode($e);
    }
}