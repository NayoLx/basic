<?php
/**
 * Created by PhpStorm.
 * User: lixuan
 * Date: 2018/8/8
 * Time: 10:35
 * 方法类
 */
namespace app\helpers;

use Jazz\Helper\Unit;
use Yii;

class Mypublic
{
    //获取登录需要的数据
    static function get_post_data($url, $user, $pass, $ip) {
        $md5_key = md5($ip);
        $md5_value = md5(md5($ip) . "sise");
        $datas = $md5_key . "=" . $md5_value;//拼凑post需要数据
        $datas .= Mypublic::getRT($url) . "&username=" .$user . "&password=" . $pass;
        return $datas;
    }

    //获取random和token
    static function getRT($url) {
        $datas="";
        //获取头部cookie
        $content = Utils::getResponse($url);//获取头部内容
        $cookie_name = "/JSESSIONID=(.*?)!/";
        preg_match_all($cookie_name, $content, $cookie_info);
        $cookie_value = $cookie_info[1][0];
        //获取random
        $random_name = "/<input id=\"random\"   type=\"hidden\"  value=\"(.*?)\"  name=\"random\" \/>/";
        preg_match_all($random_name, $content, $random_info);
        $random_value = $random_info[1][0];

        //获取Token的算法(需要url+cookie+random)
        $value = strtoupper(md5($url . $cookie_value . $random_value));
        $len = strlen($value);
        $randomlen = strlen($random_value);
        $token = '';
        for($index = 0; $index < $len; $index++) {
            $token .= $value[$index];
            if ($index < $randomlen) $token .= $random_value[$index];
        }

        $datas .= "&random=" . $random_value . "&token=" . $token;
        return $datas;
    }

    //获取个人详细页面的studentid
    static function get_indexpage($check){
        $preg_name = "/studentid=(.*?)'/";
        preg_match_all($preg_name, $check, $stu);
        $student = $stu[0][0];
        $studentid1 = trim($student, "studentid  '");
        $id = ltrim($studentid1, "=");
        return $id;
    }

    //获取个人详细信息
    static function get_page($page) {
        $page = iconv('GBK', 'UTF-8', $page);
        $preg_name = "/<div align=\"left\">(.*?)<\/div>/si";
        preg_match_all($preg_name, $page, $detail);

        $stunum = Mypublic::trimall($detail[1][2]);
        $stuname = Mypublic::trimall($detail[1][3]);
        $year = Mypublic::trimall($detail[1][4]);
        $major = Mypublic::trimall($detail[1][5]);
        $idcard = Mypublic::trimall($detail[1][6]);
        $schoolemail = Mypublic::trimall($detail[1][7]);
        $headmaster = Mypublic::trimall($detail[1][8]);
        $instructor = Mypublic::trimall($detail[1][9]);
        $necessAllGrade = Mypublic::trimall($detail[1][10]);
        $alreadyGrade = Mypublic::trimall($detail[1][11]);
        $unreadyGrade = Mypublic::trimall($detail[1][13]);
        $grade = Mypublic::trimall($detail[1][21]);

        //专业：[1][5] 身份证：[1][6] 邮箱：[1][7] 班主任：[1][8] 辅导员[1][9]
        Yii::$app->db->createCommand()->update('user_student',[
            'stuname' => $stuname,
            'major' => $major,
            'year' => $year,
            'idcard' => $idcard,
            'schoolemail' => $schoolemail,
            'headmaster' => $headmaster,
            'instructor' => $instructor,
            'necessAllGrade' => $necessAllGrade ,
            'alreadyGrade' => $alreadyGrade ,
            'unreadyGrade' => $unreadyGrade ,
            'grade' => $grade,
        ],'stunumber = :username')->bindValue(':username', $stunum)->execute();		

        return $detail;

    }

    //去除字符串前头的空格和换行
    static function trimall($str){
        $qian=array(" ","　","\t","\n","\r");
        return str_replace($qian, '', $str);
    }

    //获取课表
    static function setJson($Url, $cookies, $schoolyear, $semester) {
        Mypublic::get_schedular(Utils::get_content($Url, $cookies));
        echo Mypublic::get_sqlschedular(Utils::get_content($Url, $cookies), $schoolyear, $semester);
    }

    //地图获取课表
    static function setJsonmap($Url, $cookies) {
        echo Mypublic::get_schedular(Utils::get_content($Url, $cookies));
    }

    //判断用
    static function setJsoncheck($Url, $cookies) {
        return Mypublic::get_schedular(Utils::get_content($Url, $cookies));
    }

    static function load($Url, $cookies, $schoolyear, $semester){
        Mypublic::get_schedular(Utils::get_content($Url, $cookies));
        return Mypublic::get_sqlschedular(Utils::get_content($Url, $cookies), $schoolyear, $semester);
    }

    /**********************防止翻转乱码*****************************/
    static function utf8_strrev($str) {
        preg_match_all('/./us', $str, $ar);
        return join('', array_reverse($ar[0]));
    }

    //截取课表数据
    static function get_schedular($content) {
        $content = iconv('GBK', 'UTF-8', $content);
        $preg_name = "/<td width=\"70%\" nowrap>\<span class=\"style15\">&nbsp;\<span class=\"style16\">学号: (.*?) &nbsp;姓名: (.*?) &nbsp;年级: (.*?) &nbsp;专业:(.*?)<\/span> <\/span><\/td>/";
        preg_match_all($preg_name, $content, $name_info);
        $stu = array();
        $stu['stuNumber'] = isset($name_info[1][0]) ? $name_info[1][0] : '';
        $stu['stuName'] = isset($name_info[2][0]) ? $name_info[2][0] : '';
        $stu['stugrade'] = isset($name_info[3][0]) ? $name_info[3][0] : '';
        $stu['stuMajor'] = isset($name_info[4][0]) ? $name_info[4][0] : '';

        if ($stu['stuNumber'] == '') {
            return '';
            exit ;

        } else {
            /*存储学号和姓名到json*/
            $data["stuNumber"] = isset($name_info[1][0]) ? $name_info[1][0] : '';
            $data["stuName"] = isset($name_info[2][0]) ? $name_info[2][0] : '';
            $stu['stugrade'] = isset($name_info[3][0]) ? $name_info[3][0] : '';

            Yii::$app->db->createCommand()->update('user_student',[
                'stuname' => $name_info[2][0],
            ],'stuNumber = :username')->bindValue(':username', $name_info[1][0])->execute();
            for($i=3; $i>=0; $i--)
            {
                $data["stugrade"]["0"][] = $stu['stugrade'] + $i;
            }
            for($x=1; $x<=2; $x++)
            {
                $data["stugrade"]["1"][] = $x;
            }

            $stugrade = serialize($data["stugrade"]);

            Yii::$app->db->createCommand()->update('user_stugrade',[
                'stugrade' => $stugrade,
            ],'stunumber = :username')->bindValue(':username', $name_info[1][0])->execute();

            preg_match_all("/(教学周: 第(.*?)周)/", $content, $teach_time);

            $stu_now_week = isset($teach_time[2][0]) ? $teach_time[2][0]:'';

            $preg_time = "/<td width='10%' align='center' valign='top' class='font12'>(.*?)节<br>(.*?)<\/td><td width='10%' align='left' valign='top' class='font12'>/";
            preg_match_all($preg_time, $content, $schooltime);

            $class_time = array();
            $class_time['time_num'] = count($schooltime[2]);
            //获取有多少个时间段，注释的是之前以为12:30-13:50是休息时间需要跳过，但现在好多在线课都在12:30-13:50，所以正常获取了
            for ($num = 0; $num < $class_time['time_num']; $num++) {
                $class_time[] = $schooltime[2][$num];
                $num1 = $num + 1;
            }

            $preg = "/<td width='10%' align='left' valign='top' class='font12'>(.*?)<\/td>/si";
            preg_match_all($preg, $content, $arr);

            $subject = array();
            static $vline = 0;
            static $hline = 1;
            $arr_size = count($arr[1]);

            /*********************************正则截取字段并且存入json*********************************************/
            $json_string="";
            for ($subject_count = 0; $subject_count != $arr_size; $subject_count++) {
                if ($hline > 7) {
                    $hline = 1;
                    $vline += 1;
                }

                $subject[$hline][$vline] = $arr[1][$subject_count];
                if ($arr[1][$subject_count] != "&nbsp;") {
                    $class_content1 = $subject[$hline][$vline];
                    $class_content = Mypublic::utf8_strrev($class_content1);

                    /* 课程名称*/
                    $preg_hz = "/(.*?)[\x{4e00}-\x{9fa5}a-zA-Z 0-9]{2,}\(/u";
                    preg_match_all($preg_hz, $class_content, $class_name_info);
                    $class_name1 = substr($class_name_info[0][0], 0, strlen($class_name_info[0][0]));
                    $class_name2 = substr($class_content, strlen($class_name1), strlen($class_name_info[0][0]) - 1);
                    $class_name = Mypublic::utf8_strrev($class_name2);
                    /*周数和教师名称*/
                    $preg_name = "/[\x{4e00}-\x{9fa5}a-zA-Z 0-9]{2,}\(/u";
                    preg_match_all($preg_name, $class_content, $class_details1);
                    $class_details = Mypublic::utf8_strrev($class_details1[0][0]);
                    /*去掉空格*/
                    $preg_hz = "/(.*?)[\s　]+/s";
                    preg_match_all($preg_hz, $class_details, $hz);

                    /*输出教学班和任课老师*/
                    $preg_name1 = "/[a-zA-Z 0-9]{2,}/";
                    $class_learn_class1 = $hz[0][0];
                    //教学班
                    preg_match_all($preg_name1, $class_learn_class1, $class_learn);
                    $class_learn_class = $class_learn[0][0];
                    $class_teacher = $hz[0][1];

                    $WeekNum = count($hz[0], 0) - 3;
                    $class_weeks = null;
                    /*截取课室*/
                    $class_rom = substr($class_name_info[0][0], 0, strlen($class_name_info[0][0]) - strlen($class_details1[0][0]));
                    $preg_name = "/[a-zA-Z 0-9]{2,}/";
                    preg_match_all($preg_name, $class_rom, $class_room_info);
                    $class_room = Mypublic::utf8_strrev($class_room_info[0][0]);

                    $which_class = $vline + 1;
                    $all_WeekNum = $WeekNum + 1;

                    /*保存为键值对*/
                    $data["time"][$vline][] = array(
                        "day" => "row$hline",
                        "class" => "教学班$class_learn_class",
                        "classname" => "$class_name",
                        "teacher" => "任课老师$class_teacher",
                        "classroom" => "$class_room",
                        "week" => "一共$all_WeekNum"."周"
                    );

                }
                else {
                    $which_class = $vline + 1;
                    $data["time"][$vline][] = array(
                        "day" => "row$hline",
                    );
                }
                $json_string = json_encode($data, JSON_UNESCAPED_UNICODE);
//               file_put_contents('web/json/test.json', $json_string);
                $hline += 1;
            }

            return $json_string;
        }

    }

    //存储到数据库数据
    static function get_sqlschedular($content, $schoolyear, $semester) {
        $content = iconv('GBK', 'UTF-8', $content);
        $preg_name = "/<td width=\"70%\" nowrap>\<span class=\"style15\">&nbsp;\<span class=\"style16\">学号: (.*?) &nbsp;姓名: (.*?) &nbsp;年级: (.*?) &nbsp;专业:(.*?)<\/span> <\/span><\/td>/";
        preg_match_all($preg_name, $content, $name_info);
        $stu = array();
        $stu['stunumber'] = isset($name_info[1][0]) ? $name_info[1][0] : ''; //学号

        //判断数据库里有无重复数据
        $test = Yii::$app->db->createCommand('select * from user_scgedular where stunumber = :username and schoolyear = :schoolyear and semster = :semster')
            ->bindValue(':username',  $stu['stunumber'])
            ->bindValue(':schoolyear', $schoolyear)
            ->bindValue(':semster', $semester)->queryOne();

        if ($test == true) {
            return '';
            exit ;
        } else {
            $preg = "/<td width='10%' align='left' valign='top' class='font12'>(.*?)<\/td>/si";
            preg_match_all($preg, $content, $arr);
            $sc = $arr[1];
            $sc_detail = array();
            for ($i = 0; $i < 8; $i++) {
                $sc_detail[] = array_slice($sc, $i * 7, 7);
                Yii::$app->db->createCommand()->insert('user_scgedular', [
                    'stunumber' => $stu['stunumber'],
                    'schoolyear' => $schoolyear,
                    'semster' => $semester,
                    'time' => $i + 1,
                    'monday' => isset($sc_detail[$i][0]) ? $sc_detail[$i][0] : '',
                    'tuesday' => isset($sc_detail[$i][1]) ? $sc_detail[$i][1] : '',
                    'wednesday' => isset($sc_detail[$i][2]) ? $sc_detail[$i][2] : '',
                    'thursday' => isset($sc_detail[$i][3]) ? $sc_detail[$i][3] : '',
                    'friday' => isset($sc_detail[$i][4]) ? $sc_detail[$i][4] : '',
                    'saturday' => isset($sc_detail[$i][5]) ? $sc_detail[$i][5] : '',
                    'sunday' => isset($sc_detail[$i][6]) ? $sc_detail[$i][6] : '',
                ])->execute();
            }
        }
        $testx = Yii::$app->db->createCommand('select * from user_scgedular where stunumber = :username and schoolyear = :schoolyear and semster = :semster')
            ->bindValue(':username', $stu['stunumber'])
            ->bindValue(':schoolyear', $schoolyear)
            ->bindValue(':semster', $semester)
            ->queryAll();
        return json_encode($testx);
    }
    
	//获取必修课程
	static function get_obligatory($page, $username)
    {
        $page = iconv('GBK', 'UTF-8', $page);
        $preg_table = "/<table width=\"90%\" class=\"table\" align=\"center\">(.*?)<\/table>/s";
        preg_match($preg_table, $page, $table);

        $ppp = $table[0];
        $preg_td = "/<a.*?>(.*?)<\/a>/";
        preg_match_all($preg_td, $ppp, $td);
        $preg_time = "/<td>[0-9]{4}.*?<\/td>/";
        preg_match_all($preg_time, $ppp, $tr);

        $len = count($td[1]);

        for ($i = 0; $i < $len; $i++) {
            $time = isset($tr[0][$i]) ? $tr[0][$i] : '未修';
            $time = trim($time, "<td> </td>");
            $obligatory[$time][] = $td[1][$i];
        }

        $db_obli = serialize($obligatory);
        Yii::$app->db->createCommand()->update('user_student', [
            'obligatory' => $db_obli
        ], 'stunumber = :stunum')->bindValue('stunum', $username)->execute();

        return $obligatory;

    }
}
