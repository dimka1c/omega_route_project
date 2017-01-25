<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 023 23.01.17
 * Time: 10:21
 */

namespace app\models;


use yii\db\ActiveRecord;

class Clients extends ActiveRecord
{

    public static function tableName()
    {
        return 'clients';
    }

    public function rules()
    {
        return [
            [['name', 'region', 'city', 'address'], 'required'],
            [['contact', 'geo_coordinates'], 'trim'],
        ];
    }
}