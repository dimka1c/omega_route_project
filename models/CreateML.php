<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 023 23.01.17
 * Time: 12:54
 */

namespace app\models;


use yii\db\ActiveRecord;

class CreateML extends ActiveRecord
{

    public static function tableName()
    {
        return 'ml';
    }

    public function rules()
    {
        return [
            [['disch_procedure', 'client'], 'required'],
            [['phone_mp','otp_tov_clnt', 'numb_nakl'], 'trim'],
            [['inomarka', 'dostavkaTT', 'vozvrat'], 'boolean'],
            [['kol_poz', 'kol_mest', 'massa', 'v'], 'trim'],
            [['time_start', 'time_end', 'time_run', 'type_route'], 'trim'],
            ['id_ml', 'required'],
            ['id_ml', 'integer'],
            ['data_ml', 'trim'],
            [['on_driver', 'from_driver', 'driver'], 'integer'],
            [['type', 'flight'], 'trim'],
        ];
    }

}