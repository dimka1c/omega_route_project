<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>

<?php if(Yii::$app->session->getFlash('driver_add')): ?>
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong></strong> Добавлен новый водитель
    </div>
<?php endif; ?>

<div class="container-fluid default-top">
    <div class="row">
        <div class="col-md-2">

            <div class="list-group font-menu-admin">
                <a href="/admin/admin" class="list-group-item">Список водителей</a>
                <a href="/driver/add-driver" class="list-group-item">Добавить водителя</a>
                <a href="#" class="list-group-item">Пользователи</a>
            </div>

        </div>
        <div class="col-md-9">
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

    </div>
</div>