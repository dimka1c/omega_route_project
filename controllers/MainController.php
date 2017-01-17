<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 017 17.01.17
 * Time: 10:06
 */

namespace app\controllers;
use app\models\LoginForm;
use yii\web\Controller;

class MainController extends Controller
{

    public function actionIndex()
    {
        $this->layout = 'not_autorized';
        $model = new LoginForm();
        return $this->render('index', ['model' => $model]);
    }

}