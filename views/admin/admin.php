<?php


use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Voditel;
use yii\helpers\Url;
use yii\widgets\Pjax;

$script = <<< JS

    function ChangeStatus(id) {

        $.ajax({
            url: '/web/driver/change-status',
            type: 'POST',
            cache: false,
            data: 'id='+id,
            success: function(data){
                console.log('вернулись данные - ' + data);
                $('#driver_status_'+id).html(data);
            },
            beforeSend: function (id) {
                console.log('перед отправкой на сервер ' + id);
            },
            complete: function() {
                console.log('запрос завершен');
            },
            error: function (data) {
                console.log('произошла ошибка в запросе');
            },
    
        });

    }
    
    function RemoveDriver(id) {
        
        $.ajax({
            url: '/web/driver/delete-driver',
            type: 'POST',
            cache: false,
            data: 'id='+id,
            success: function(data){
                //console.log('вернулись данные - ' + data);
                $('#driver_status_'+id).removeClass('label-success label-danger');
                $('#driver_status_'+id).addClass('label-warning');
                $('#driver_status_'+id).html(data);
            },
            beforeSend: function (id) {
                //console.log('перед отправкой на сервер ' + id);
            },
            complete: function() {
                //console.log('запрос завершен');
            },
            error: function (data) {
                //console.log('произошла ошибка в запросе');
            },
    
        });
        
    }
JS;
//маркер конца строки, обязательно сразу, без пробелов и табуляции
$this->registerJs($script, yii\web\View::POS_END);


?>

<div class="container-fluid default-top">
    <div class="row">
        <div class="col-md-2">

            <div class="list-group">
                <a href="/web/admin/admin" class="list-group-item">Список водителей</a>
                <a href="/web/driver/add-driver" class="list-group-item">Добавить водителя</a>
                <a href="#" class="list-group-item">Пользователи</a>
            </div>

        </div>
        <div class="col-md-9">
            <?php

                $dataProvider = new \yii\data\ActiveDataProvider([
                    'query' => Voditel::find()->limit(50),
                    'pagination' => [
                        'pageSize' => 50,
                    ],
                ]);
                //var_dump($dataProvider);

                echo GridView::widget([
                        'dataProvider' => $dataProvider,
                        'id' => 'driver_table',
                        'rowOptions' => function ($model, $key, $index, $grid) {
                            if($model->active == false) {
                                return ['style' => 'background-color:#D1D1D1;'];
                            }
                        },
                        'columns' => [
                            [ 'class' => 'yii\grid\SerialColumn' ],
                            [
                                'class' => \yii\grid\DataColumn::className(),
                                'attribute' => 'voditel_name',
                                'format' => 'html',
                                'label' => 'Водитель',
                                'value' => function ($model, $key, $index, $column) {
                                    return Html::a(Html::encode($model->voditel_name), ['view-driver', 'id' => $model->id]);
                                }
                            ],
                            [
                                'class' => \yii\grid\DataColumn::className(),
                                'attribute' => 'voditel_nomer_auto',
                                'format' => 'text',
                                'label' => 'Авто',
                            ],
                            [
                                'class' => \yii\grid\DataColumn::className(),
                                'attribute' => 'voditel_phone',
                                'format' => 'text',
                                'label' => 'Телефон',
                            ],
                            [
                                /**
                                 * Название поля модели
                                 */
                                'attribute' => 'active',
                                /**
                                 * Формат вывода.
                                 * В этом случае мы отображает данные, как передали.
                                 * По умолчанию все данные прогоняются через Html::encode()
                                 */
                                'format' => 'raw',
                                /**
                                 * Переопределяем отображение фильтра.
                                 * Задаем выпадающий список с заданными значениями вместо поля для ввода
                                 */
                                'filter' => [
                                    0 => 'работает',
                                    1 => 'уволен',
                                ],
                                'label' => 'Статус',
                                /**
                                 * Переопределяем отображение самих данных.
                                 * Вместо 1 или 0 выводим Yes или No соответственно.
                                 * Попутно оборачиваем результат в span с нужным классом
                                 */
                                'value' => function ($model, $key, $index, $column) {
                                    $active = $model->{$column->attribute} === 1;
                                    $status = $active ? 'работает' : 'уволен';
                                    /*
                                    return Html::button($status,
                                            [
                                                'class' => 'label label-' . ($active ? 'success' : 'danger'),
                                                'id' => 'driver_status_' . $key,
                                                'onclick' => 'ChangeStatus('.$key.'); return false']
                                    );
                                    */

                                    return Html::a($status, ['', 'id' => $key], [
                                        'class' => 'label label-' . ($active ? 'success' : 'danger'),
                                        'id' => 'driver_status_' . $key,
                                        'onClick' => 'ChangeStatus(' . $key . ')'
                                    ]);
                                },
                            ],

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{delete}',
                                'buttons' => [
                                    'delete' => function ($url, $model, $key) {
                                        return Html::a('', ['', 'id' => $key],
                                            [
                                                'class' => 'glyphicon glyphicon-remove',
                                                'style' => 'color: red',
                                                'id' => 'remove_driver_' . $key,
                                                'onclick' => 'RemoveDriver(' . $key .'); return false;',
                                            ]);
                                    },
                                ],
                            ],
                        ],
                    ]);
            ?>
        </div>
    </div>



</div>
