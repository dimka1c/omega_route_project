<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>


<div class="container-fluid default-top">

    <?php if(Yii::$app->session->getFlash('change_driver')): ?>
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong></strong> Данные успешно изменены.
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-2">

            <div class="list-group font-menu-admin">
                <a href="/admin/admin" class="list-group-item">Список водителей</a>
                <a href="/driver/add-driver" class="list-group-item">Добавить водителя</a>
                <a href="#" class="list-group-item">Пользователи</a>
            </div>

        </div>
        <div class="col-md-10">

            <div class="container">
                <div class="row">
                    <div class="btn-group col-md-offset-1">
                        <button type="button" class="btn btn-default">Пробеги водителя</button>
                        <button type="button" class="btn btn-default">Маршруты водителя</button>
                        <button type="button" class="btn btn-default">
                            Рабочие дни &nbsp;
                            <span class="badge"><?= $day ?> </span>
                        </button>
                    </div>

                    <h1><?= Html::encode($this->title) ?></h1>

                    <h4 class="col-md-10 col-md-offset-1">Данные о водителе: <b style="color: #1e57b7;"><?= $model->voditel_name ?></b></h4>

                    <?php $form = ActiveForm::begin([
                        'id' => 'add-driver-form',
                        'action' => [ 'driver/view-driver?id=' . $model->id . '&save=1' ],
                        'layout' => 'horizontal',
                        'fieldConfig' => [
                            'template' => "{label}<div class=\"col-md-4\">{input}</div><div class=\"col-md-6\">{error}</div>",
                            'labelOptions' => ['class' => 'col-md-2  control-label'],
                        ],
                    ]); ?>

                    <?= $form->field($model, 'voditel_name')->textInput()->label('ФИО водителя') ?>

                    <?= $form->field($model, 'voditel_nomer_auto')
                        ->label('Номер авто')
                        ->textInput() ?>

                    <?= $form->field($model, 'voditel_phone')->textInput()->label('Телефоны') ?>

                    <div class="form-group">
                        <div class="col-md-1 col-md-offset-2">
                            <?= Html::submitButton('Изменить', ['class' => 'btn btn-primary', 'name' => 'register-button']) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>

                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <?php var_dump($day) ?>
                            <div class="container" id="tek_month">
                                <div class="row col-md-6" style="background-color: #f2f2f2">
                                    <p>Отработано дней за этот месяц: <b>19</b></p>
                                    <p>Пробег за текущий месяц: <b>7287 км</b></p>
                                    <p>В текущем месяце досталено груза: <b>12.082 т</b></p>
                                    <p>В текущем месяце доставлено клиентов: <b>276</b></p>
                                </div>
                            </div>
                            <hr>
                            <div class="container" id="old_month">
                                <p>Отработано дней в феврале <b>22</b></p>
                                <p>За февраль 2017г пробег: <b>7287 км</b></p>
                                <p>Доставлено груза: <b>12.082 т</b></p>
                                <p>Количество обслуженных клиентов: <b>276</b></p>
                            </div>
                        </div>
                    </div>
                </div>


            </div>



        </div>




    </div>
</div>