<?php

use yii\helpers\Html;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="container">
    <div class="header">
        <div class="col-xs-1">
            <?= Html::img('@web/images/omega_logo.jpg', ['class' => 'img-responsive top-img', 'width
' => '60', 'height' => '60']) ?>
        </div>
        <div class="clearfix">
            <?= Html::a('Почта', ['admin/mail'], ['class' => 'btn btn-default active', 'role' => 'button']) ?>
            <?= Html::a('Создание общего МЛ', ['admin/createml'], ['class' => 'btn btn-default', 'role' => 'button']) ?>
            <?= Html::a('Маршруты', ['admin/route'], ['class' => 'btn btn-default', 'role' => 'button']) ?>
            <?= Html::a('Пробеги', ['admin/runs'], ['class' => 'btn btn-default', 'role' => 'button']) ?>
            <?= Html::a('Администрирование', ['admin/admin'], ['class' => 'btn btn-default', 'role' => 'button']) ?>
            <?= Html::a(Yii::$app->user->identity->name_user . ' ( выход ) ', ['main/logout'], ['class' => 'btn btn-default pull-right', 'role' => 'button']) ?>
        </div>
    </div>

    <div class="container">

        <?= $content ?>

    </div>

</div>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
