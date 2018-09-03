<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/8/8
 * Time: 9:18
 */
namespace app\models;

use Yii;
use yii\base\Model;

class MyForm extends Model
{
    public $name;
    public $email;

    public function rules()
    {
        return [
            [['name','email'],'required'],
            ['email','email']
        ];
    }
}