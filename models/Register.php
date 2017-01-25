<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 019 19.01.17
 * Time: 10:55
 */

namespace app\models;


use yii\db\ActiveRecord;

class Register extends ActiveRecord
{

    public static function tableName()
    {
        return 'users';
    }

}