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

<div class="container-fluid default-top">


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
    foreach ($mail as $key=>$val): ?>
        <tr>
            <td><?= $val['uid'] ?></td>
            <td><?= $val['email']?></td>
            <td><?= $val['subj']?></td>
            <td><?= Html::button('Обработать', ['class' => 'btn btn-xs btn-warning', 'id' => 'mail_'.$val['uid'],
                'onclick' => 'createML('.$val['uid'].'); return false']) ?></td>
            <td width="80px"><?= Html::img('@web/images/work.gif', ['class' => 'img-responsive', 'width
                ' => '50', 'height' => '50', 'style' => $img_visible, 'id' => 'img_'.$val['uid']]) ?></td>
            <td id="process" width="200px"></td>
        </tr>
    <?php endforeach ?>

    </table>

<?php endif ?>

</div>



