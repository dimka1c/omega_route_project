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

    <h4>Добавление нового водителя</h4>

    <?php $form = ActiveForm::begin([
        'id' => 'add-driver-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "<div class=\"col-md-2\">{label}</div><div class=\"col-md-4\">{input}</div><div class=\"col-md-6\">{error}</div>",
            'labelOptions' => ['class' => 'col-md-2  control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'voditel_name')->textInput()->label('ФИО водителя') ?>

    <?= $form->field($model, 'voditel_nomer_auto')
        ->label('Номер авто')
        ->textInput() ?>

    <?= $form->field($model, 'voditel_phone')->textInput()->label('Телефоны') ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary', 'name' => 'register-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>