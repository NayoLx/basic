<?php namespace app\helpers;
/**
 * Created by PhpStorm.
 * User: lixuan
 * Date: 2018/8/7
 * Time: 15:35
 * 爬虫类
 */
class Utils
{

    /**
     * 获取头部信息
     */
    static function getResponse($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, true); //返回头信息
        curl_setopt($ch, CURLOPT_NOBODY, false); //
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //返回数据
        $content = curl_exec($ch); //执行并存储结果
        curl_close($ch);
        return $content;
    }

    /**
     * 登录
     */
    static function login_post($loginurl, $cookie, $post) {
        $curl = curl_init(); //初始化curl模块
        curl_setopt($curl, CURLOPT_URL, $loginurl); //登录提交的地址
        curl_setopt($curl, CURLOPT_HEADER, false); //是否显示头信息
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); //是否自动显示返回的信息
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie); //
        curl_setopt($curl, CURLOPT_POST, true); //post方式提交
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post); //要提交的信息
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        $rs = curl_exec($curl); //执行cURL闭cURL资源，并且释放系统资源
        curl_close($curl);
        return $rs;
    }

    /**
     * 获取页面和课表
     */
    static function get_content($Url, $cookies) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $Url); //定义地址
        curl_setopt($ch, CURLOPT_HEADER, false); //显示头信息
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); //跟随转跳
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //以数据流返回，是
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookies); //读取cookie
        // curl_setopt($ch, CURLOPT_COOKIE, $cookie);//设置cookie
        $rs = curl_exec($ch); //执行cURL抓取页面内容
        curl_close($ch);
        return $rs;
    }

    /**
     *发送json格式的数据，到api接口 -xzz0704
     */
    static function https_curl_json($url, $data, $type){
        if($type=='json'){//json $_POST=json_decode(file_get_contents('php://input'), TRUE);
            $headers = array("'content-type': 'application/json'");
            $data = json_encode($data);
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
        $output = curl_exec($curl);
        if (curl_errno($curl)) {
            echo curl_error($curl);//捕抓异常
        }
        curl_close($curl);

        return $output;
    }

    /**
     *  post提交数据
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据
     * @return url响应返回的html
     */
    static function sendPost($url, $datas) {
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
    static function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data.$appkey)));
    }
}