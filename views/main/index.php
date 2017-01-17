<?php

use yii\bootstrap\ActiveForm;

?>

<div class="container">
    <p>Введите логин и пароль:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'login')->input('text'); ?>

    <?= $form->field($model, 'password')->input('password'); ?>

    <?php ActiveForm::end() ?>

</div>
