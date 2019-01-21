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

    //快递公司list
    public function actionKuaidilist()
    {
        $params = [];
        $keyword = Yii::$app->request->post('keyword', '');
        if($keyword != '') {
            $array_list = Yii::$app->db->createCommand("select * from kuaidi_bird WHERE k_name LIKE :k_name")->bindValue(':k_name', '%'.$keyword.'%')->queryAll();
        }
        else {
            $array_list = Yii::$app->db->createCommand('select * from kuaidi_bird')->queryAll();
        }
        $params['array_list'] = $array_list;

        return $this->render('kuaidi_list', $params);
    }

    //新增的页面
    public function actionNewkuaidi()
    {
        return $this->render('newkuaidi');
    }

    public function actionAddnew()
    {
        $e = new \stdClass();
        $kTagName = Yii::$app->request->post('kTagName','');
        $kTagvalue = Yii::$app->request->post('kTagvalue','');
        $first = substr($kTagvalue, 0,1);

        Yii::$app->db->createCommand()->insert('api_baidu',[
            'headfield' => $first,
            'name' => $kTagName,
            'value' => $kTagvalue,
        ])->execute();

        $e -> success = true;
        $e -> title = '保存成功';
        return json_encode($e);
    }

    public function actionEditnew()
    {
        $e = new \stdClass();
        $id = Yii::$app->request->post('id','');
        $kTagName = Yii::$app->request->post('kTagName','');
        $kTagvalue = Yii::$app->request->post('kTagvalue','');
        $first = substr($kTagvalue, 0,1);

        Yii::$app->db->createCommand()->update('api_baidu',[
            'headfield' => $first,
            'name' => $kTagName,
            'value' => $kTagvalue,
        ],'id = :id')->bindValue(':id', $id)->execute();

        $e -> success = true;
        $e -> title = '保存成功';
        return json_encode($e);
    }

    //修改的页面
    public function actionEditkuaidi()
    {
        $request = \yii::$app->request;
        $id = $request->get('id');
        $params = [];
        $choose = Yii::$app->db->createCommand('select * from kuaidi_bird where id = :id')->bindValue(':id', $id)->queryOne();
        $params['choose'] = $choose;

        return $this->render('editkuaidi', $params);
    }
    //删除
    public function actionDeletekuaidi()
    {

    }

}