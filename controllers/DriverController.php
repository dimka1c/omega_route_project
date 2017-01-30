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
use yii\base\Controller;

class DriverController extends \yii\base\Controller
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
            if (isset($_POST['id'])) {
                $model = CreateML::findAll(['driver' => $_POST['id']]);
                if (!empty($model)) {
                    echo 'невозможно удалить';
                } else {

                }
            }
        }
    }

    public function actionAddDriver()
    {
       $model = new Voditel();
        $this->layout = 'admin';
        return $this->render('add_driver', compact('model'));

    }
}