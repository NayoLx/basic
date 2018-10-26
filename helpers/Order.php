<?php
/**
 * Created by PhpStorm.
 * User: lixuan
 * Date: 2018/10/26
 * Time: 9:25
 */
namespace app\helpers;


use Yii;

class Order
{
    /**
     * 生成订单号
     */
    static function setOrder_no($order_type){
        if ($order_type == '需要代拿') {
            return 'N' . date('Ymd').str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        }
        elseif ($order_type == '帮忙代拿') {
            return 'T' . date('Ymd').str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        }
        elseif ($order_type == '帮忙其他' || $order_type == '其他') {
            return 'O' . date('Ymd').str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        }
        else {
            return '无效订单类别';
        }
    }

    /**
     * 获取信息
     */
    static function getuser($openid)
    {
        $e = new \stdClass();
        $nickName = Yii::$app->db->createCommand('select nickName from wxdeatil where openid = :openid')->bindValue(':openid', $openid)->queryOne();
        $phone = Yii::$app->db->createCommand('select phone from wxdeatil where openid = :openid')->bindValue(':openid', $openid)->queryOne();
        $avatarUrl = Yii::$app->db->createCommand('select avatarUrl from wxdeatil where openid = :openid')->bindValue(':openid', $openid)->queryOne();
        $stunumber = Yii::$app->db->createCommand('select stunumber from wxdeatil where openid = :openid')->bindValue(':openid', $openid)->queryOne();
        $e -> nickName = $nickName['nickName'];
        $e -> phone = $phone['phone'];
        $e -> avatarUrl = $avatarUrl['avatarUrl'];
        $e -> stunumber = $stunumber['stunumber'];

        return $e;
    }

    /**
     * 修改订单类别
     */
    static function setType($order_type) {
        if ($order_type == '需要代拿') {
            $order_type = 'N' ;
            return $order_type;
        }
        elseif ($order_type == '帮忙代拿') {
            $order_type = 'T' ;
            return $order_type;
        }
        elseif ($order_type == '帮忙其他' || $order_type == '其他') {
            $order_type = 'O' ;
            return $order_type;
        }
        else {
            return '无效订单类别';
        }
    }
}