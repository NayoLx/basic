<?php
/**
 * Created by PhpStorm.
 * User: Lixuan
 * Date: 2018/11/19
 * Time: 16:35
 */

namespace app\controllers;

use Yii;
use yii\db\Exception;
use yii\web\Controller;
use app\helpers\Utils;

class CommentController extends Controller
{
    /**
     * 增
     */
    public static function actionAddcomment()
    {
        $openid = Yii::$app->request->post('openid', '');
        $comment = Yii::$app->request->post('comment', '');
        $postid = Yii::$app->request->post('postid', '');

        $detail = Yii::$app->db->createCommand('select nickName , avatarUrl from wxdeatil where openid = :openid')->bindValue(':openid', $openid)->queryOne();
        $time = date('y-m-d H:i',time());
        $name = $detail['nickName'];
        $avatar = $detail['avatarUrl'];

        Yii::$app->db->createCommand()->insert('comment_both', [
            'comment' => $comment,
            'user_id' => $openid,
            'time' => $time,
            'comment_name' => $name,
            'comment_avatar' => $avatar,
            'postid' => $postid,
        ])->execute();

        return json_encode(array('status' => 'true'));
    }

    /**
     * 查
     */
    public static function actionGetcomment()
    {
        $e = new \stdClass();
        $e -> success = false;

        $postid = Yii::$app->request->post('postid', '');
        $detail = Yii::$app->db->createCommand('select * from comment_both where postid = :postid')->bindValue(':postid', $postid)->queryAll();

        if ($detail == '' || $detail == [] || $detail == null) {
            $e -> success = true;
            $e -> error = '暂无留言';
            return json_encode($e);
        }

        $e -> detail = $detail;
        $e -> success = true;

        return json_encode($e);
    }

}