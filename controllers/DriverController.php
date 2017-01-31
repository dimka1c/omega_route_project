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

}