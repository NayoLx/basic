<?php
/**
 * Created by PhpStorm.
 * User: Lixuan
 * Date: 2018/11/2
 * Time: 18:31
 */

namespace app\controllers;

use Yii;
use yii\db\Exception;
use yii\web\Controller;
use app\helpers\Utils;

class ExpressController extends Controller
{
    /**
     * 即时查询
     * Json方式 查询订单物流轨迹
     * EBusinessID快递鸟用户id, Appkey快递api密钥
     * ShipperCode快递公司编码，LogisticCode物流订单号 '266246489521'
     * status物流状态: 0-无轨迹，1-已揽收，2-在途中，3-签收,
     */
    public function actionGetordertraces()
    {
        $EBusinessID = '1398770';
        $AppKey = 'ed1f2c12-2fe3-4ad5-9f78-0e7f745fde7d';
        $ReqURL = 'http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx';
        $url = 'http://api.kdniao.com/api/dist';


//        $shipper = 'ZTO';
//        $logistc = '266246489521';
        $logistc = Yii::$app->request->post('logistc', '');
        $shipper = Yii::$app->request->post('shipper', '');


        $requestData = "{'OrderCode':'', 'ShipperCode': '$shipper', 'LogisticCode': '$logistc', }";
        $enData = urlencode($requestData);

        $datas = array(
            'EBusinessID' => $EBusinessID,
            'RequestType' => '1002',
            'RequestData' => $enData,
            'DataType' => '2',
        );
        $datas['DataSign'] = Utils::encrypt($requestData, $AppKey);

        $result = Utils::sendPost($ReqURL, $datas);

        return $result;
    }

    /**
     * @return js
     * @throws 获取数据库里所有的快递公司并进行格式转化
     */
    public function  actionGetkuaidi()
    {
        $allkuaidi = Yii::$app->db->createCommand('select * from kuaidibird')->queryAll();

        $kuaiditype = array();

        for ($i = 0; $i < count($allkuaidi)-1; $i++) {
            $type = $allkuaidi[$i]['headfield'];
            $kuaiditype[$type][] = $allkuaidi[$i];
        }

        return json_encode($kuaiditype);
    }


}