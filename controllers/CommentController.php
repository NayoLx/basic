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

    /**
     * @ 点赞模板消息
     * 微信Api的获取与数据获取
     * 点赞通知 {{keyword1.DATA}}
     * 点赞时间 {{keyword2.DATA}}
     * 点赞人 {{keyword3.DATA}}
     * 查看方式 {{keyword4.DATA}}
     */
    public function actionGetcommentapi()
    {
        $e = new \stdClass();
        $e -> success = false;

        $touser = Yii::$app->request->post('touser', '');
        $template_id = Yii::$app->request->post('template_id', '');
        $form_id = Yii::$app->request->post('form_id', '');
        $keyword1 = Yii::$app->request->post('keyword1', '');  //点赞通知
        $stu = Yii::$app->db->createCommand('select stunumber from wxdeatil where openid = :openid')->bindValue(':openid', $touser)->queryOne();
        $keyword2 = Yii::$app->db->createCommand('select stuname from student where stunumber = :stunumber')->bindValue(':stunumber', $stu['stunumber'])->queryOne(); //点赞人名称
        $keyword3 = date('y-m-d H:i:s',time()); //点赞时间
        $keyword4 = Yii::$app->request->post('keyword4', ''); //查看方式
        $access_token = Yii::$app->request->post('access_token', '');

        $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=' . $access_token;

//       return json_encode('https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='. $access_token);
        $value = array(
            'keyword1'=>array(
                'value' => $keyword1,
            ),
            'keyword2'=>array(
                'value' => $keyword2['stuname'],
            ),
            'keyword3'=>array(
                'value' => $keyword3,
            ),
            'keyword4'=>array(
                'value' => $keyword4,
            )
        );


        $dd = array();

        $dd['touser'] = $touser;
        $dd['template_id'] = $template_id;
        $dd['page'] = Yii::$app->request->post('page', '');;
        $dd['form_id'] = $form_id;
        $dd['data'] = $value;

//       return json_encode($dd);
        $result = utils::https_curl_json($url, $dd, 'json');

        if($result){
            echo json_encode(array('state'=>5,'msg'=>$result));
        }else{
            echo json_encode(array('state'=>5,'msg'=>$result));
        }

    }

}