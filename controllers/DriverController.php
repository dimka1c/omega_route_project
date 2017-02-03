<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 030 30.01.17
 * Time: 17:10
 */

namespace app\controllers;

use app\models\CreateML;
use app\models\Voditel;
use Yii;
use yii\web\Controller;

class DriverController extends Controller
{

    public function actionChangeStatus()
    {
        if( Yii::$app->request->isAjax) {
            if (isset($_POST['id'])) {
                $model = Voditel::findOne(['id' => $_POST['id']]);
                if (!empty($model)) {
                    $status = $model->active;
                    if ($status == 1) {
                        $model->active = 0;
                        echo 'уволен';
                    } else {
                        $model->active = 1;
                        echo 'работает';
                    }
                    $model->save();
                }
            };
        }
    }

    public function actionDeleteDriver()
    {
        if( Yii::$app->request->isAjax) {
            if (Yii::$app->request->post('id')) {
                $id = Yii::$app->request->post('id');
                $model = CreateML::find()->where(['driver' => $id])->count();
                if ($model > 0) {
                    echo 'невозможно удалить';
                } else {
                    Voditel::deleteAll(['id' => $id]);
                    echo 'удален';
                }
            }
        }
    }

    public function actionAddDriver()
    {
       if (Yii::$app->user->can('admin')) {
           $this->layout = 'admin';
           $model = new Voditel();
           if ($model->load(Yii::$app->request->post())) {
               if ( $model->validate() ) {
                   if($model->save()) {
                       Yii::$app->session->setFlash('driver_add', 'В базу данных добавлен новый водитель', true );
                       return $this->refresh();
                   };
               }
           }

           return $this->render('add_driver', compact('model'));

       }
    }

    public function actionViewDriver()
    {
        if (Yii::$app->user->can('admin')) {
            $this->layout = 'admin';
            if ($id = Yii::$app->request->get('id')) {
                if ($save = Yii::$app->request->get('save')) {  //производим запись в базу
                    $model = new Voditel();
                    if ($model->load(Yii::$app->request->post())) {
                        if ($model->validate()) {
                            Yii::$app->session->setFlash('change_driver', 'Данные изменены', true);
                            return $this->refresh();
                        }
                    }

                        if ($model::updateAll(
                            [
                                'voditel_name' => '35_name',
                                'voditel_nomer_auto' => '35_number_auto',
                                'voditel_phone' => '35_phone'
                            ],
                            ['id' => 35]
                        ))
                        {
                            Yii::$app->session->setFlash('change_driver', 'Данные изменены', true);
                            //return $this->render('view_driver', compact('model'));
                            return $this->refresh();
                        };


                    }
                }
                $model = Voditel::findOne(['id' => $id]);
                $data_driver = CreateML::find(['driver' => $id])->asArray()->where(['data_ml' => '2017-01-23'])->count();
                $day = CreateML::find(['driver' => $id])->groupBy('data_ml')->asArray()->count();
                return $this->render('view_driver', compact('model', 'data_driver', 'day'));
            }
    }

    public function actionChangeDriver()
    {
        if (Yii::$app->user->can('admin')) {
            if ($id = Yii::$app->request->get('id')) {
                $model = Voditel::findOne(['id' => $id]);
                if ($model->validate()) {
                    if (Voditel::updateAll(
                        [
                            'voditel_name' => 'ВВВ',
                            'voditel_nomer_auto' => 'sdfdsf',
                            'voditel_phone' => 'phone'
                        ],
                        ['id' => 35]
                    )) {
                        Yii::$app->session->setFlash('change_driver', 'Данные изменены', true);
                        return $this->refresh();
                    };
                }
            }
        }
    }

    public function actionRuns()
    {
        if (Yii::$app->user->can('admin')) {
            $this->layout = 'admin';
            echo 'пробеги водителей';
        }
    }
}