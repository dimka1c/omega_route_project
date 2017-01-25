<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 019 19.01.17
 * Time: 15:04
 */

namespace app\models;


use yii\db\ActiveRecord;

class MailHost extends ActiveRecord
{

    public static function tableName()
    {
        return 'mail_domens';
    }

}