<?php

namespace app\commands;

use yii\console\Controller;
use Yii;

class RbacController extends Controller
{

    public function actionInit()
    {

        $auth = Yii::$app->authManager;

        // добавляем разрешения
        $readMail = $auth->createPermission('readMail');
        $readMail->description = 'Чтение почты';
        $auth->add($readMail);

        $createXLSBook = $auth->createPermission('createXLSBook');
        $createXLSBook->description = 'создание общей книги XLS';
        $auth->add($createXLSBook);

        $editUser = $auth->createPermission('editUser');
        $editUser ->description = 'работа с пользователями';
        $auth->add($editUser );

        $editCoordinates = $auth->createPermission('editCoordinates');
        $editCoordinates->description = 'редактирование координат';
        $auth->add($editCoordinates);

        $editRuns = $auth->createPermission('editRuns');
        $editRuns->description = 'редактирование пробегов';
        $auth->add($editRuns);

        $editRouting = $auth->createPermission('editRouting');
        $editRouting->description = 'редактирование маршрутов водителей';
        $auth->add($editRouting);

        // добавляем роль "driver" и даём роли разрешения
        $driver = $auth->createRole('driver');
        $driver->description = 'водитель';
        $auth->add($driver);
        $auth->addChild($driver, $editRuns); //редактирование пробегов

        // добавляем роль "админ" и даём роли разрешения
        $admin = $auth->createRole('admin');
        $admin->description = 'администратор';
        $auth->add($admin);
        $auth->addChild($admin, $readMail);
        $auth->addChild($admin, $createXLSBook);
        $auth->addChild($admin, $editUser);
        $auth->addChild($admin, $editCoordinates);
        $auth->addChild($admin, $editRuns);
        $auth->addChild($admin, $editRouting);

    }

}