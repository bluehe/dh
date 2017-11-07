<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dh\models\Website;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-12">

        <?php
        $form = ActiveForm::begin(['id' => 'website-form',
                    'options' => ['class' => 'form-horizontal', 'onsubmit' => 'return false;'],
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-md-6\">{input}</div>\n<div class=\"col-md-3\">{error}</div>",
                        'labelOptions' => ['class' => 'col-md-3 control-label'],
                    ],
        ]);
        ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'is_open')->radioList(Website::$List['is_open'], ['itemOptions' => ['labelOptions' => ['class' => 'radio-inline']]]) ?>

        <div class="col-md-6 col-xs-6 text-right">

            <?= Html::submitButton('确定', ['class' => 'btn btn-primary']) ?>

        </div>
        <div class="col-md-6 col-xs-6 text-left">
            <?= Html::resetButton('取消', ['class' => 'btn btn-default', 'data-dismiss' => "modal"]) ?>
        </div>


        <?php ActiveForm::end(); ?>


    </div>
</div>

<script>
<?php $this->beginBlock('submit') ?>
    $('body').off('submit').on('submit', '#website-form', function () {
        $.ajax({
            url: '<?= Url::toRoute(['ajax/website-add', 'id' => $model->cid]) ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: "json",
            success: function (data) {
                if (data.stat === 'success') {
                    var str = '<div class="list-group-item' + (data.is_open ? '' : ' list-group-item-warning') + '" id="' + data.id + '">'
                            + '<img src="/api/getfav?url=' + data.host + '">'
                            + ' <a class="clickurl" target="_blank" href="' + data.url + '" title="' + data.title + '">' + data.title + '</a>'
                            + '<div class="dropdown pull-right">'
                            + '<span class="dropdown-toggle" id="dropdownMenu' + data.id + '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-caret-square-o-down" title="操作"></i></span>'
                            + '<div class="dropdown-menu content-icon" aria-labelledby="dropdownMenu' + data.id + '">'
                            + ' <i class="fa fa-share-alt website-share" title="推荐分享"></i>'
                            + ' <i class="fa fa-edit website-edit" title="编辑"></i>'
                            + ' <i class="fa fa-trash-o website-delete" title="删除"></i>'
                            + (data.is_open ? ' <i class="fa fa-eye-slash website-open" title="私有"></i>' : ' <i class="fa fa-eye website-open" title="公开"></i>')
                            + '</div></div></div>';
                    $('#user-modal').modal('hide');
                    $('.category[id=<?= $model->cid ?>]').find('.list-group').append(str);
                    if($('.category[id=<?= $model->cid ?>]').find('.website-content .list-group-item').length>=10){
                         $('.category[id=<?= $model->cid ?>]').find('.add_page').hide();
                    }
                    my_alert('success', '添加成功！', 3000);
                }else if(data.stat==='fail'){
                $('#user-modal').modal('hide');
                my_alert('danger', data.msg, 3000);
                }

            }
        });
    });


<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['submit'], \yii\web\View::POS_END); ?>