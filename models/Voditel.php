<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 023 23.01.17
 * Time: 12:34
 */

namespace app\models;


use yii\db\ActiveRecord;

class Voditel extends ActiveRecord
{

    public static function tableName()
    {
        return 'voditel';
    }

    public function rules()
    {
        return [
            [['voditel_name', 'voditel_nomer_auto'], 'required'],
            [['voditel_name', 'voditel_nomer_auto'], 'trim'],
            ['voditel_phone', 'trim']
        ];
    }

}