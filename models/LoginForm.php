<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 017 17.01.17
 * Time: 10:29
 */

namespace app\models;


use yii\base\Model;
use Yii;
use yii\bootstrap\Html;
use yii\helpers\Url;

class LoginForm extends Model
{

    public $login;
    public $password;
    public $rememberMe;

    public function rules()
    {
        return [
            [['login', 'password'] , 'required', 'message' => 'поле не может быть пустым'],
            ['rememberMe', 'boolean'],
            ['password', 'validPassword'],
        ];
    }

    public function validPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверное имя пользователя или пароль');
            }
        }
        return true;
    }


    public function getUser()
    {
        return User::findByUsername($this->login);
    }


    public function login()
    {
        if ($this->validate()) {
            $usr = $this->getUser();
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
}

