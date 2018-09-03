<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/8/8
 * Time: 9:26
 */
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\helpers\Mypublic;
use app\helpers\Utils;

class MyController extends Controller
{

    public function actionLogin2()
    {
        return $this->render('login2');
    }

    public function actionMyscgedular()
    {
        return $this->render('myscgedular');
    }

    public function actionMypersonal()
    {
        return $this->render('personal');
    }
	
	public function actionMyobligatory()
    {
        return $this->render('obligatory');
    }
	
    public function actionLoginpost()
    {
        $username = Yii::$app->request->post('username', '');
        $password = Yii::$app->request->post('password', '');

        $cookie = dirname(__FILE__).'/cookie.txt';//保存cookie在本地
        $url = "http://class.sise.com.cn:7001/sise/";//主页URl
        $loginUrl = "http://class.sise.com.cn:7001/sise/login_check_login.jsp"; //登录url
        $schedularUrl = "http://class.sise.com.cn:7001/sise/module/student_schedular/student_schedular.jsp"; //课程表url
        $indexUrl = "http://class.sise.com.cn:7001/sise/module/student_states/student_select_class/main.jsp"; //主页url

        $content = Utils::getResponse($url);//获取头部内容
        $cookie_name = "/JSESSIONID=(.*?)!/";
        preg_match_all($cookie_name, $content, $cookie_info);
        $cookie_value = $cookie_info[1][0];

        //判断数据库里没无数据
        $test = Yii::$app->db->createCommand('select * from student where stuNumber = :username')->bindValue(':username', $username)->queryOne();
        if ($test == true){
            //获取登录时需要的数据
            $logindatas = Mypublic::get_post_data($url, $username, $password, "183.14.29.189");
            header("Content-type: text/html; charset=utf-8");
            //登录
            Utils::login_post($loginUrl, $cookie, $logindatas);

            //判断是否登录成功
            $check = Mypublic::load($schedularUrl, $cookie);

         if ($check == true){
                Yii::$app->db->createCommand()->update('student',[
                    'stuNumber' => $username,
                    'password' => $password,
                    'cookie' => $cookie_value,
                ],'stuNumber = :username')->bindValue(':username', $username)->execute();//update增加到数据
                //获取studentid
                $studentid = Mypublic::get_indexpage(Utils::get_content($indexUrl, $cookie));
                $detailUrl = "http://class.sise.com.cn:7001/SISEWeb/pub/course/courseViewAction.do?method=doMain&studentid=".$studentid;
                Mypublic::get_page(Utils::get_content($detailUrl, $cookie));
            }
            echo json_encode($arrayName = array('state' => $check!='' ? true:false));//获取课表的数据
            

        }
        else {
            //获取登录时需要的数据
            $logindatas = Mypublic::get_post_data($url, $username, $password, "183.14.29.189");
            header("Content-type: text/html; charset=utf-8");
            //登录
            Utils::login_post($loginUrl, $cookie, $logindatas);

            //判断是否登录成功
            $check = Mypublic::load($schedularUrl, $cookie);

            if ($check == true){
                Yii::$app->db->createCommand()->insert('student',[
                    'stuNumber' => $username,
                    'password' => $password,
                    'cookie' => $cookie_value,
                ])->execute();//insert增加到数据库
                //获取studentid
                $studentid = Mypublic::get_indexpage(Utils::get_content($indexUrl, $cookie));
                $detailUrl = "http://class.sise.com.cn:7001/SISEWeb/pub/course/courseViewAction.do?method=doMain&studentid=".$studentid;
                Mypublic::get_page(Utils::get_content($detailUrl, $cookie));
            }
	        echo json_encode($arrayName = array('state' => $check!='' ? true:false));//获取课表的数据
            
        }

//        //获取studentid
//        $studentid = Mypublic::get_indexpage(get_content($indexUrl, $cookie));
//        $detailUrl = "http://class.sise.com.cn:7001/SISEWeb/pub/course/courseViewAction.do?method=doMain&studentid=".$studentid;
//        $detail = Utils::get_content($detailUrl, $cookie);

    }
    public function actionAcgedular()
    {
        /***********************获取不同学期的课表***************************/
        $schoolyear = Yii::$app->request->post('schoolyear', '');
        $semester = Yii::$app->request->post('semester', '');

        $cookie = dirname(__FILE__).'/cookie.txt';//保存cookie在本地

        if (!empty($schoolyear) && !empty($semester)) {
            $schedularUrl = "http://class.sise.com.cn:7001/sise/module/student_schedular/student_schedular.jsp?schoolyear=".$schoolyear."&semester=".$semester; //课程表url
            Mypublic::get_sqlschedular(Utils::get_content($schedularUrl, $cookie), $schoolyear, $semester);
            Mypublic::setJson($schedularUrl, $cookie);
        }
        else {
            $schedularUrl = "http://class.sise.com.cn:7001/sise/module/student_schedular/student_schedular.jsp"; //课程表url
            Mypublic::get_sqlschedular(Utils::get_content($schedularUrl, $cookie), $schoolyear, $semester);
            Mypublic::load($schedularUrl, $cookie);
        }
    }
	
	public function actionPersonal()
	{
	    $indexUrl = "http://class.sise.com.cn:7001/sise/module/student_states/student_select_class/main.jsp";
		$cookie = dirname(__FILE__).'/cookie.txt';
		$detail = '';

		$studentid = Mypublic::get_indexpage(Utils::get_content($indexUrl, $cookie));
		$detailUrl = "http://class.sise.com.cn:7001/SISEWeb/pub/course/courseViewAction.do?method=doMain&studentid=".$studentid;
        $detail = Mypublic::get_page(Utils::get_content($detailUrl, $cookie));
		
		echo json_encode($detail);
		
	}
	
	public function actionObligatory()
	{
		$indexUrl = "http://class.sise.com.cn:7001/sise/module/student_states/student_select_class/main.jsp";
		$cookie = dirname(__FILE__).'/cookie.txt';
		$obligatory = '';

		$studentid = Mypublic::get_indexpage(Utils::get_content($indexUrl, $cookie));
		$detailUrl = "http://class.sise.com.cn:7001/SISEWeb/pub/course/courseViewAction.do?method=doMain&studentid=".$studentid;
        $obligatory = Mypublic::get_obligatory(Utils::get_content($detailUrl, $cookie));
		
		echo json_encode($obligatory);
	}
}
