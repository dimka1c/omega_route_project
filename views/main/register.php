<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>

<?php if(Yii::$app->session->getFlash('register_save')): ?>
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong>Внимание!</strong> Данные отправлены. Свяжитесь с администратором.
        </div>
<?php endif; ?>

<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <h4>Регистрация нового польователя</h4>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'name_user')->textInput()->label('ФИО') ?>

    <?= $form->field($model, 'login', ['enableAjaxValidation' => true])
                ->label('Логин')
                ->textInput() ?>

    <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>

    <?= $form->field($model, 'confirm_password')->passwordInput()->label('Подтвердите пароль') ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary', 'name' => 'register-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>