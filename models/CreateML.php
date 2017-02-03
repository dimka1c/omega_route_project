<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 023 23.01.17
 * Time: 12:54
 */

namespace app\models;


use yii\db\ActiveRecord;
use Yii;

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


    /** записываем в таблицу log_ml
     *  данные о том, что письмо с
     *  данным uid на данный момент обрабатывается
     */
    public function setScriptStatus($uid)
    {
            return Yii::$app->db->createCommand()->insert('log_ml', [
                            'id_ml' => $uid,
                            'data_ml' => date('Y-M-D'),
                            'status_ml' => 'process',
                            'autor_ml' => Yii::$app->user->id
                    ])->execute();

    }


    public function getScriptStatus($uid)
    {
        $status = Yii::$app->db->createCommand('
                    SELECT 
                        ml.id,
                        ml.id_ml,
                        ml.data_ml,
                        ml.status_ml,
                        ml.error_code,
                        users.name_user as autor
                    FROM 
                        log_ml as ml
                    INNER JOIN users ON users.id = autor_ml
                    WHERE id_ml=:id
                ')
            ->bindValue(':id', $uid)
            ->queryOne();

        return $status;
    }

}