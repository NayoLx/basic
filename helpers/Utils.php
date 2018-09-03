<?php namespace app\helpers;
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/8/7
 * Time: 15:35
 */
class Utils
{
    //获取头部信息
    static function getResponse($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, true); //返回头信息
        curl_setopt($ch, CURLOPT_NOBODY, false); //
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //返回数据
        $content = curl_exec($ch); //执行并存储结果
        curl_close($ch);
        return $content;
    }
    //登录
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
    //获取页面和课表
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
}