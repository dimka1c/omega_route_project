<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 017 17.01.17
 * Time: 10:06
 */

namespace app\controllers;

use app\models\LoginForm;
use app\models\Register;
use app\models\RegisterForm;
use yii\web\Controller;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\web\Response;

class MainController extends Controller
{
    public $layout = 'main';

    public function actionIndex()
    {

        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->user->can('admin')) {
                return $this->redirect(Yii::$app->user->returnUrl);
            } elseif (Yii::$app->user->can('driver')) {
                return $this->redirect(['driver/index']);
            }
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            //авторизовали пользователя
            if (Yii::$app->user->can('admin')) {
                return $this->redirect(Yii::$app->user->returnUrl);
            } elseif (Yii::$app->user->can('driver')) {
                return $this->redirect(['driver/index']);
            }

        }
        return $this->render('index', ['model' => $model]);

    }

    public function actionRegister()
    {
        $model = new RegisterForm();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if($model->load(Yii::$app->request->post()) && $model->validate()) {
            $reg = new Register();
            $reg->login = $model->login;
            $reg->name_user = $model->name_user;
            $reg->password = Yii::$app->getSecurity()->generatePasswordHash($model->password);
            $reg->data_registration	= date("Y-m-d H:i:s");
            $reg->access = 0;
            $reg->admin = 0;
            $reg->region = 0;
            $reg->auth_key = Yii::$app->security->generateRandomString(32);
            $reg->access_token = Yii::$app->security->generateRandomString(32);
            if($reg->save()) {
                Yii::$app->session->setFlash('register_save', 'Данные отправлены. Свяжитесь с администратором', true );
                return $this->refresh();
            };

        }

        return $this->render('register', ['model' => $model]);

    }


    public function actionLogout()
    {

        Yii::$app->user->logout();
        return $this->redirect(Yii::$app->user->loginUrl);
    }

}