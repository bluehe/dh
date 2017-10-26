<?php
/* @var $this yii\web\View */

use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;

$this->title = '首页';

mb_regex_encoding("UTF-8");
?>

<!-- Main row -->
<div class="row">
    <!-- Left col -->
    <section class="col-lg-7 connectedSortable">
        <!-- TO DO List -->

        <div class="box box-primary repairorder">
            <div class="box-header ui-sortable-handle" style="cursor: move;">


                <h3 class="box-title"><i class="fa fa-wrench"></i> 报修广场</h3>


            </div>
            <!-- /.box-header -->
            <div class="box-body">


            </div>
            <!-- /.box-body -->

        </div>

        <div class="box box-primary pickuporder">
            <div class="box-header ui-sortable-handle" style="cursor: move;">


                <h3 class="box-title"><i class="fa fa-suitcase"></i> 拾物招领</h3>


            </div>
            <!-- /.box-header -->

            <div class="box-body">


            </div>
            <!-- /.box-body -->


        </div>





    </section>
    <!-- /.Left col -->
    <!-- right col (We are only adding the ID to make the widgets sortable)-->
    <section class="col-lg-5 connectedSortable">
        <div class="box box-primary suggestorder">
            <div class="box-header ui-sortable-handle" style="cursor: move;">


                <h3 class="box-title"><i class="fa fa-commenting-o"></i> 投诉建议</h3>


            </div>
            <!-- /.box-header -->

            <div class="box-body">


            </div>
            <!-- /.box-body -->


        </div>

    </section>
    <!-- right col -->
</div>
<!-- /.row (main row) -->
<?php
Modal::begin([
    'id' => 'view-modal',
    'header' => '<h4 class="modal-title"></h4>',
    'options' => [
        'tabindex' => false
    ],
]);
Modal::end();
?>

<script>
<?php $this->beginBlock('index') ?>
    $(".connectedSortable").sortable({
        placeholder: "sort-highlight",
        connectWith: ".connectedSortable",
        handle: ".box-header, .nav-tabs",
        forcePlaceholderSize: true,
        zIndex: 999999
    });
    $(".connectedSortable .box-header, .connectedSortable .nav-tabs-custom").css("cursor", "move");
    //jQuery UI sortable for the todo list
    $(".todo-list").sortable({
        placeholder: "sort-highlight",
        handle: ".handle",
        forcePlaceholderSize: true,
        zIndex: 999999
    });
<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['index'], \yii\web\View::POS_END); ?>
