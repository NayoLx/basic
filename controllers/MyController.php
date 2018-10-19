<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/8/8
 * Time: 9:26
 */
namespace app\controllers;

use Yii;
use yii\db\Exception;
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
    public function actionMysavedetail()
    {
        return $this->render('savedetail');
    }
    public function actionMysavebinddetail()
    {
        return $this->render('savebinddetail');
    }
    public function actionMygetgrade()
    {
        return $this->render('getgrade');
    }
	/**
	 * 登陆
	 * */
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

        $grad = Yii::$app->db->createCommand('select * from stugrade where stunumber = :username')->bindValue(':username', $username)->queryOne();
        if ($grad == false) {
            Yii::$app->db->createCommand()->insert('stugrade',[
                'stunumber' => $username,
            ])->execute();
        }


        //判断数据库里没无数据
        $test = Yii::$app->db->createCommand('select * from student where stuNumber = :username')->bindValue(':username', $username)->queryOne();
        if ($test == true){

//            $getpass = Yii::$app->db->createCommand('select password from student where stuNumber = :username')->bindValue(':username', $username)->queryOne();
//
//            if ($password == $getpass['password']) {
//
//                echo json_encode($arrayName = array('state'=>true));//获取课表的数据
//            }

            //获取登录时需要的数据
            $logindatas = Mypublic::get_post_data($url, $username, $password, "183.14.30.179");
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
                ],'stuNumber = :username')->bindValue(':username', $username)->execute();//insert增加到数据库

                //获取studentid
                $studentid = Mypublic::get_indexpage(Utils::get_content($indexUrl, $cookie));
                $detailUrl = "http://class.sise.com.cn:7001/SISEWeb/pub/course/courseViewAction.do?method=doMain&studentid=".$studentid;
                Mypublic::get_page(Utils::get_content($detailUrl, $cookie));
            }
            echo json_encode($arrayName = array('state' => $check!='' ? true : false));//获取课表的数据

        }
        else {
            //获取登录时需要的数据
            $logindatas = Mypublic::get_post_data($url, $username, $password, "183.14.30.179");
            header("Content-type: text/html; charset=utf-8");
            //登录
            Utils::login_post($loginUrl, $cookie, $logindatas);

            //判断是否登录成功
            $check = Mypublic::load($schedularUrl, $cookie, '2018' , '1');

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
	        echo json_encode($arrayName = array('state' => $check!='' ? true : false));//获取课表的数据
            
        }

//        //获取studentid
//        $studentid = Mypublic::get_indexpage(get_content($indexUrl, $cookie));
//        $detailUrl = "http://class.sise.com.cn:7001/SISEWeb/pub/course/courseViewAction.do?method=doMain&studentid=".$studentid;
//        $detail = Utils::get_content($detailUrl, $cookie);

    }

    /**
	 * 获取课表
	 **/
    public function actionAcgedular()
    {
        /***********************获取不同学期的课表***************************/
        $username = Yii::$app->request->post('stunumber', '');
        $schoolyear = Yii::$app->request->post('schoolyear', '');
        $semester = Yii::$app->request->post('semester', '');

        $cookie = dirname(__FILE__).'/cookie.txt';//保存cookie在本地

        /*如果数据库有数据则直接调用数据库里的数据*/
        $test = Yii::$app->db->createCommand('select * from scgedular where stunumber = :username and schoolyear = :schoolyear and semster = :semster')
            ->bindValue(':username', $username)
            ->bindValue(':schoolyear', $schoolyear)
            ->bindValue(':semster', $semester)
            ->queryAll();

        if ($test == true) {

            return json_encode($test);

        }
        else {
            if (!empty($schoolyear) && !empty($semester)) {
                $schedularUrl = "http://class.sise.com.cn:7001/sise/module/student_schedular/student_schedular.jsp?schoolyear=".$schoolyear."&semester=".$semester; //课程表url
                Mypublic::get_sqlschedular(Utils::get_content($schedularUrl, $cookie), $schoolyear, $semester);

                $testx = Yii::$app->db->createCommand('select * from scgedular where stunumber = :username and schoolyear = :schoolyear and semster = :semster')
                    ->bindValue(':username', $username)
                    ->bindValue(':schoolyear', $schoolyear)
                    ->bindValue(':semster', $semester)
                    ->queryAll();
                return json_encode($testx);

//                Mypublic::setJson($schedularUrl, $cookie,  $schoolyear, $semester);
            }
            else {
                $schedularUrl = "http://class.sise.com.cn:7001/sise/module/student_schedular/student_schedular.jsp"; //课程表url
                Mypublic::get_sqlschedular(Utils::get_content($schedularUrl, $cookie), $schoolyear, $semester);
                Mypublic::load($schedularUrl, $cookie, $schoolyear, $semester);
            }
        }
    }

    public  function  actionGetgrade() {
        $username = Yii::$app->request->post('stunumber', '');

        $grade = Yii::$app->db->createCommand('select stugrade from stugrade where stunumber = :username')->bindValue(':username', $username)->queryOne();

        $getgrade = unserialize($grade['stugrade']);

        echo json_encode($getgrade);
    }

	/**
	 * 获取个人信息
	 **/
	public function actionPersonal()
	{
	    $indexUrl = "http://class.sise.com.cn:7001/sise/module/student_states/student_select_class/main.jsp";
		$cookie = dirname(__FILE__).'/cookie.txt';

		$studentid = Mypublic::get_indexpage(Utils::get_content($indexUrl, $cookie));
		$detailUrl = "http://class.sise.com.cn:7001/SISEWeb/pub/course/courseViewAction.do?method=doMain&studentid=".$studentid;
        $detail = Mypublic::get_page(Utils::get_content($detailUrl, $cookie));
		
		echo json_encode($detail);
		
	}
	/**
	 * 获取课程
	 */
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

     // 保存微信信息
    public function actionSavedetail()
    {
        $nickName = Yii::$app->request->post('nickName', '');
        $gender = Yii::$app->request->post('gender', '');
        $country = Yii::$app->request->post('country', '');
        $city = Yii::$app->request->post('city', '');
        $avatarUrl = Yii::$app->request->post('avatarUrl', '');
        $province = Yii::$app->request->post('province', '');
        $openid = Yii::$app->request->post('openid', '');

        //判断数据库里没无数据
        $test = Yii::$app->db->createCommand('select * from wxdeatil where openid = :openid')->bindValue(':openid', $openid)->queryOne();
        if ($openid != '' && $test == false) {
            //$test==false 说明无数据，新增数据
            Yii::$app->db->createCommand()->insert('wxdeatil', [
                'nickName' => $nickName,
                'gender' => $gender,
                'country' => $country,
                'city' => $city,
                'avatarUrl' => $avatarUrl,
                'province' => $province,
                'openid' => $openid,
            ])->execute();
        }
        else {
            Yii::$app->db->createCommand()->update('wxdeatil', [
                'nickName' => $nickName,
                'gender' => $gender,
                'country' => $country,
                'city' => $city,
                'avatarUrl' => $avatarUrl,
                'province' => $province,
                'openid' => $openid,
            ], 'openid = :openid')->bindValue(':openid', $openid)->execute();
        }
        return;
    }

    //绑定信息
    public function actionSavebinddetail()
    {
        $openid = Yii::$app->request->post('openid', '');
        $usernumber = Yii::$app->request->post('usernumber', '');
        $password = Yii::$app->request->post('password', '');
        $phone = Yii::$app->request->post('phone', '');
        $isbind = Yii::$app->request->post('is_bind', '');

        Yii::$app->db->createCommand()->update('wxdeatil', [
                'openid' => $openid,
                'stuNumber' => $usernumber,
                'password' => $password ,
                'phone' => $phone,
                'is_bind' => $isbind,
            ], 'openid = :openid')->bindValue(':openid', $openid)->execute();
    }

}
