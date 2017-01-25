<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 019 19.01.17
 * Time: 10:00
 */

namespace app\models;

use yii\base\Model;

class RegisterForm extends Model
{

    public $name_user;
    public $login;
    public $password;
    public $confirm_password;


    public function rules()
    {
        return [
            [['login', 'password', 'confirm_password'], 'required', 'message' => 'поле обязательно для заполнения'],
            [['login', 'password', 'confirm_password'], 'trim'],
            ['login', 'string', 'min' => 5, 'message' => 'минимум 5 максимум 50 символов'],
            ['login', 'unique', 'targetClass' => Register::className(), 'targetAttribute' => 'login', 'message' => 'логин занят'],
            [['password', 'confirm_password'], 'string', 'min' => 5, 'max' => 20, 'message' => 'от 5 до 20 символов'],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],
            ['name_user', 'required', 'message' => 'поле обязательно к заполнению'],
        ];
    }


}