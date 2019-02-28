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
        $params = $this->fuzzysearch();

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

        Yii::$app->db->createCommand()->insert('kuaidi_bird',[
            'headfield' => $first,
            'k_name' => $kTagName,
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

        Yii::$app->db->createCommand()->update('kuaidi_bird',[
            'headfield' => $first,
            'k_name' => $kTagName,
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
        $id = Yii::$app->request->get('id', ' ');
        Yii::$app->db->createCommand()->update('kuaidi_bird', [
            'is_delete' => 'true',
        ], 'id = :id')->bindValue(':id', $id)->execute();

        return $this->actionKuaidilist();


    }

    //模糊查询
    public function fuzzysearch()
    {
        $keyword = Yii::$app->request->get('keyword', '');
        $array_list = Yii::$app->db->createCommand('select * from kuaidi_bird')->queryAll();

        if(!empty($keyword)) {
            $array_list = Yii::$app->db->createCommand("select * from kuaidi_bird where k_name LIKE :k_name")->bindValue(':k_name', '%'.$keyword.'%')->queryAll();
        }

        $params['array_list'] = $array_list;

        return $params;
    }

    public function actionCommentlist()
    {
        /**
         * 1****正常状态 2****删除状态
         */
        $array_list = Yii::$app->db->createCommand('select * from comment_text_detail ')->queryAll();
        $params = [];

        $params['array_list'] = $array_list;
        return $this->render('comment_text', $params);
    }

    public function actionNewcomment()
    {
        return $this->render('newcomment');
    }

    public function actionAddcomment()
    {
        $e = new \stdClass();
        $texttitle = Yii::$app->request->post('texttitle','');
        $textcomment = Yii::$app->request->post('textcomment','');
        $author = Yii::$app->session['username'];
        $time = date('y-m-d H:i:s',time());

        Yii::$app->db->createCommand()->insert('comment_text_detail',[
            'title' => $texttitle,
            'detail' => $textcomment,
            'data' => $time,
            'avater' => 'https://wx.qlogo.cn/mmopen/vi_32/nfMPoEP0ibtzpJxMqUPGiaojVvCRicATEyNhWvAvPeAibV11IVL8EODcTMZ2whYjGy2RKibJxv4D0p5uULXq94hypibw/132',
            'author' => $author,
            'status' => '1',
        ])->execute();

        $e -> success = true;
        $e -> title = '保存成功';
        return json_encode($e);
    }

    public function actionEditcommentac()
    {
        $e = new \stdClass();
        $id = Yii::$app->request->post('id','');
        $texttitle = Yii::$app->request->post('texttitle','');
        $textcomment = Yii::$app->request->post('textcomment','');
        $time = date('y-m-d H:i:s',time());

        Yii::$app->db->createCommand()->update('comment_text_detail',[
            'title' => $texttitle,
            'detail' => $textcomment,
            'updata_time' => $time,
        ],'id = :id')->bindValue(':id', $id)->execute();

        $e -> success = true;
        $e -> title = '保存成功';
        return json_encode($e);
    }

    public function actionEditcomment()
    {
        $request = \yii::$app->request;
        $id = $request->get('id');
        $params = [];
        $choose = Yii::$app->db->createCommand('select * from comment_text_detail where id = :id')->bindValue(':id', $id)->queryOne();
        $params['detail'] = $choose;

        return $this->render('editcomment', $params);
    }

    public function actionDeletecomment()
    {
        $e = new \stdClass();
        $id = Yii::$app->request->GET('id','');
        $e->id = $id;
        $time = date('y-m-d H:i:s',time());

        Yii::$app->db->createCommand()->update('comment_text_detail',[
            'status' => '2',
            'updata_time' => $time,
        ],'id = :id')->bindValue(':id', $id)->execute();

        $e -> success = true;
        $e -> title = '保存成功';

        return json_encode($e);
    }
}