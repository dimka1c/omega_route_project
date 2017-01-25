<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 020 20.01.17
 * Time: 12:20
 */

namespace app\models;


use yii\db\ActiveRecord;

class System extends ActiveRecord
{

    public static function tableName()
    {
        return 'system';
    }

}