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
            <?= Html::a('Вход', ['main/index'], ['class' => 'btn btn-default active', 'role' => 'button']) ?>
            <?= Html::a('Регистрация', ['main/register'], ['class' => 'btn btn-default', 'role' => 'button']) ?>
            <?= Html::a('Восстановление пароля', ['main/restore'], ['class' => 'btn btn-default', 'role' => 'button']) ?>

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


