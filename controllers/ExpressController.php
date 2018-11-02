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
use app\helpers\Mypublic;
use app\helpers\Utils;

class ExpressController extends controller
{
    /**
     * Json方式 查询订单物流轨迹
     */
    public function getOrderTracesByJson()
    {
        defined('EBusinessID') or define('EBusinessID', '请到快递鸟官网申请http://kdniao.com/reg');
        //电商加密私钥，快递鸟提供，注意保管，不要泄漏
        defined('AppKey') or define('AppKey', '请到快递鸟官网申请http://kdniao.com/reg');
        //请求url
        defined('ReqURL') or define('ReqURL', 'http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx');


        $requestData = "{'OrderCode':'', 'ShipperCode': 'YTO', 'LogisticCode': ''}";

        $datas = array(
            'EBusinessID' => EBusinessID,
            'RequestType' => '1002',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $datas['DataSign'] = self::encrypt($requestData, Appkey);

        $result = self::sendPost(ReqURL, $datas);

        return $result;
    }

    /**
     *  post提交数据
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据
     * @return url响应返回的html
     */
    public function sendPost($url, $datas) {
        $temps = array();
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);
        }

        $post_data = implode('&', $temps);
        $url_info = parse_url($url);

        if(empty($url_info['port']))
        {
            $url_info['port']=80;
        }
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader.= "Host:" . $url_info['host'] . "\r\n";
        $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader.= "Connection:close\r\n\r\n";
        $httpheader.= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets.= fread($fd, 128);
        }
        fclose($fd);

        return $gets;
    }

    /**
     * 电商Sign签名生成
     * @param data 内容
     * @param appkey Appkey
     * @return DataSign签名
     */
    public function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data.$appkey)));
    }
}