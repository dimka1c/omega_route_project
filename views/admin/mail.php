<?php

use yii\widgets\Pjax;
use yii\helpers\Html;

$this->registerJsFile('@web/js/mail.js', ['depends' => [\yii\web\JqueryAsset::className()   ]]);

if ( Yii::$app->session->get('createml_157') === true) {
    $img_visible = 'display:inline';
} else {
    $img_visible = 'display:none';
}
?>

<?php if(Yii::$app->session->getFlash('error_status_log')): ?>
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong>Внимание!</strong>  Ошибка при работе с базой данных. Повторите пожалуйста позднее.
    </div>
<?php endif; ?>

<div class="container-fluid default-top">

    <div class="row" id="error">
        <?php if(Yii::$app->session->getFlash('no_mail')): ?>
            <div class="alert alert-success alert-dismissable col-md-6 col-md-offset-1">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong></strong> В данный момент почтовый ящик пуст
            </div>
        <?php endif; ?>
    </div>

<?php

if (!empty($mail)): ?>

    <table class="table table-hover">
        <tр>
            <th>№</th>
            <th>отправитель</th>
            <th>тема</th>
            <th></th>
        </tр>



    <?php
    foreach ($msg_status as $key=>$val): ?>
        <tr>
            <td><?= $val['uid'] ?></td>
            <td><?= $val['email']?></td>
            <td><?= $val['subj']?></td>
            <td><?= Html::button($val['msg'], ['class' => $val['button_class'], 'disabled' => $val['disabled'],
                    'id' => 'mail_'.$val['uid'], 'onclick' => 'createML('.$val['uid'].'); return false']) ?></td>
            <td width="80px"><?= Html::img('@web/images/work.gif', ['class' => 'img-responsive', 'width
                ' => '50', 'height' => '50', 'style' => $img_visible, 'id' => 'img_'.$val['uid']]) ?></td>
            <td id="process_<?= $val['uid'] ?>"  width="200px"></td>
        </tr>
    <?php endforeach ?>

    </table>

<?php endif ?>

</div>



