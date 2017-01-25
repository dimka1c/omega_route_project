<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>

<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="container">
        <?= Yii::$app->user->isGuest ?>
        <br>
        <?= Yii::$app->user->loginUrl ?>
        <br>
        <?= Yii::$app->user->returnUrl ?>
        <br>
    </div>

    <div>
        Контроллер ADMIN
        после авторизации
    </div>

</div>