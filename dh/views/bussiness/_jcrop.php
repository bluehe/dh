<?php

use bluehe\jcrop\jCrop;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$form = ActiveForm::begin(['id' => 'jcrop-form']);
Modal::begin([
    'id' => 'logo-crop',
    'size' => 'modal-sm',
    'header' => '图片编辑',
    'footer' => '<button type="submit" class="btn btn-primary">确定</button><a href="#" class="btn btn-default" data-dismiss="modal">取消</a>',
    'clientOptions' => ['show' => true]
]);



echo jCrop::widget([
// Image URL
    'url' => $url,
    // options for the IMG element
    'imageOptions' => [
        'id' => 'image',
        'alt' => '图片',
    ],
    // Jcrop options (see Jcrop documentation [http://blog.csdn.net/lybwwp/article/details/17307583])
    'jsOptions' => array(
        'allowSelect' => false,
        'aspectRatio' => 1,
        'setSelect' => [0, 0, 270, 270],
        'bgOpacity' => 0.4,
        'selection' => true,
        'boxWidth' => 270,
        'boxHeight' => 360,
    )
]);
?>
<input type="hidden" name="image[url]" value="<?= $url ?>">
<?php
Modal::end();
ActiveForm::end();
?>
<script>
<?php $this->beginBlock('submit') ?>
    $('body').off('submit').on('submit', '#jcrop-form', function () {

        $.ajax({
            url: '<?= Url::toRoute(['logo-crop']) ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: "json",
            success: function (data) {
                $('#logo-crop').modal('hide');
                if (data.stat === 'success') {
                    $('#recommend-img').val(data.file);
                    $('.recommend-img').attr('src', data.file);

                } else if (data.stat === 'fail') {

                }

            }
        });
        return false;
    });


<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['submit'], \yii\web\View::POS_END); ?>


