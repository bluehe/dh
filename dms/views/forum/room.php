<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use dms\models\Room;

/* @var $this yii\web\View */
/* @var $searchModel dms\models\RoomSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '房间管理';
$this->params['breadcrumbs'][] = ['label' => '楼苑设置', 'url' => ['forum/forum']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="room-index">

    <div class="box box-primary">
        <div class="box-body">
            <?php // echo $this->render('_search', ['model' => $searchModel]);    ?>

            <p>
                <?= Html::a('创建房间', ['forum/room-create'], ['class' => 'btn btn-success']) ?>
            </p>
            <?php Pjax::begin(); ?>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'id' => 'grid',
                'layout' => "{summary}\n<div class=table-responsive>{items}</div>\n{pager}",
                'summary' => "第{begin}-{end}条，共{totalCount}条",
                'tableOptions' => ['class' => 'table table-bordered table-hover'],
                'rowOptions' => function($model) {
                    return ['class' => $model->rid ? '' : 'success'];
                },
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
                        'value' => //'forums.name',
                        function($model) {
                            return $model->forums->name;   //主要通过此种方式实现
                        },
                        'filter' => Room::get_room_forum(), //此处我们可以将筛选项组合成key-value形式
                        'footerOptions' => ['class' => 'hide'],
                    ],
                    [
                        'attribute' => 'floor',
                        'value' => //'floors.v',
                        function($model) {
                            return $model->floors->v;   //主要通过此种方式实现
                        },
                        'filter' => Room::get_room_floor(), //此处我们可以将筛选项组合成key-value形式
                        'footerOptions' => ['class' => 'hide'],
                    ],
                    ['attribute' => 'name', 'footerOptions' => ['class' => 'hide'],],
                    ['attribute' => 'note', 'footerOptions' => ['class' => 'hide'],],
                    ['attribute' => 'gender',
                        'value' => //'colleges.name',
                        function($model) {
                            return $model->Gender;   //主要通过此种方式实现
                        },
                        'filter' => Room::$List['gender'], //此处我们可以将筛选项组合成key-value形式
                        'footerOptions' => ['class' => 'hide'],],
                    [
                        'attribute' => 'stat',
                        'value' => //'colleges.name',
                        function($model) {
                            return $model->Stat;   //主要通过此种方式实现
                        },
                        'filter' => Room::$List['stat'], //此处我们可以将筛选项组合成key-value形式
                        'footerOptions' => ['class' => 'hide'],
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{update} {delete}', //只需要展示删除和更新
                        'buttons' => [
                            'update' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-pencil"></i> 修改', ['forum/room-update', 'id' => $key], ['class' => 'btn btn-primary btn-xs',]);
                            },
                            'delete' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-trash-o"></i> 删除', ['forum/room-delete', 'id' => $key], ['class' => 'btn btn-danger btn-xs', 'data' => ['confirm' => '删除房间将会影响相关小室及床位，此操作不能恢复，你确定要删除房间吗？',]]);
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
    $(".room-index").on("click", '.deleteall', function () {
        var keys = $("#grid").yiiGridView("getSelectedRows");
        if (keys.length && confirm("删除房间将会影响相关小室及床位，此操作不能恢复，你确定要删除房间吗？")) {
            $.post("<?= Yii::$app->urlManager->createUrl('forum/room-deletes') ?>?ids=" + keys, function (data) {
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