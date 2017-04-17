<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use dms\models\Bed;

/* @var $this yii\web\View */
/* @var $searchModel dms\models\BedSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '床位管理';
$this->params['breadcrumbs'][] = ['label' => '楼苑设置', 'url' => ['forum/forum']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bed-index">

    <div class="box box-primary">
        <div class="box-body">

            <p>
                <?= Html::a('创建床位', ['forum/bed-create'], ['class' => 'btn btn-success']) ?>
            </p>
            <?php Pjax::begin(); ?>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                "id" => "grid",
                'layout' => "{summary}\n<div class=table-responsive>{items}</div>\n{pager}",
                'summary' => "第{begin}-{end}条，共{totalCount}条",
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                'showFooter' => true, //设置显示最下面的footer
                'columns' => [
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'name' => 'id',
                        'footer' => Html::a('<i class="fa fa-trash-o"></i> 批量删除', 'javascript:void(0);', ['class' => 'btn btn-danger btn-xs deleteall']),
                        'footerOptions' => ['colspan' => 8],
                    ],
                    [
                        'attribute' => 'fid',
                        'label' => '楼苑',
                        'value' => //'forum.name',
                        function($model) {
                            return $model->forum->name;   //主要通过此种方式实现
                        },
                        'filter' => Bed::get_bed_forum(), //此处我们可以将筛选项组合成key-value形式
                        'footerOptions' => ['class' => 'hide'],
                    ],
                    [
                        'attribute' => 'floor',
                        'label' => '楼层',
                        'value' => //'floors.v',
                        function($model) {
                            return $model->floor->v;   //主要通过此种方式实现
                        },
                        'filter' => Bed::get_bed_floor(), //此处我们可以将筛选项组合成key-value形式
                        'footerOptions' => ['class' => 'hide'],
                    ],
                    ['attribute' => 'rid',
                        'value' => function($model) {
                            return $model->room->rid ? $model->room->fname . '-' . $model->room->name : $model->room->name;   //主要通过此种方式实现
                        },
                        'footerOptions' => ['class' => 'hide'],
                    ],
                    ['attribute' => 'name', 'footerOptions' => ['class' => 'hide'],],
                    ['attribute' => 'note', 'footerOptions' => ['class' => 'hide'],],
                    [
                        'attribute' => 'stat',
                        'value' => //'colleges.name',
                        function($model) {
                            return $model->Stat;   //主要通过此种方式实现
                        },
                        'filter' => Bed::$List['stat'], //此处我们可以将筛选项组合成key-value形式
                        'footerOptions' => ['class' => 'hide'],
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{update} {delete}', //只需要展示删除和更新
                        'buttons' => [
                            'update' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-pencil"></i> 修改', ['forum/bed-update', 'id' => $key], ['class' => 'btn btn-primary btn-xs',]);
                            },
                            'delete' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-trash-o"></i> 删除', ['forum/bed-delete', 'id' => $key], ['class' => 'btn btn-danger btn-xs', 'data' => ['confirm' => '删除床位将会影响相关住宿记录，此操作不能恢复，你确定要删除床位吗？',]]);
                            },
                        ],
                        'footerOptions' => ['class' => 'hide'],
                    ],
                ],
            ]);
            ?>
            <?php Pjax::end(); ?>        </div>
    </div>
</div>
<script>
<?php $this->beginBlock('delete') ?>
    $(".bed-index").on("click", '.deleteall', function () {
        var keys = $("#grid").yiiGridView("getSelectedRows");
        if (keys.length > 0 && confirm("删除床位将会影响相关住宿记录，此操作不能恢复，你确定要删除床位吗？")) {
            $.post("<?= Yii::$app->urlManager->createUrl('forum/bed-deletes') ?>?ids=" + keys, function (data) {
                if (data) {
                    window.location.reload();
                }
            });
        }
    });
</script>
<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['delete'], \yii\web\View::POS_END); ?>