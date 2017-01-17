<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 017 17.01.17
 * Time: 10:29
 */

namespace app\models;


use yii\base\Model;

class LoginForm extends Model
{

    public $login;
    public $password;
    public $rememberMe;

    public function rules()
    {
        return [
            [['login', 'password'] , 'required'],
            ['rememberMe', 'boolean']
        ];
    }
}