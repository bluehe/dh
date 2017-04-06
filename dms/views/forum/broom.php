<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use dms\models\Broom;

/* @var $this yii\web\View */
/* @var $searchModel dms\models\BroomSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '房间管理';
$this->params['breadcrumbs'][] = ['label' => '楼苑设置', 'url' => ['forum/forum']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="broom-index">

    <div class="box box-primary">
        <div class="box-body">
            <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

            <p>
                <?= Html::a('创建房间', ['forum/broom-create'], ['class' => 'btn btn-success']) ?>
            </p>
            <?php Pjax::begin(); ?>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'layout' => "{summary}\n<div class=table-responsive>{items}</div>\n{pager}",
                'summary' => "第{begin}-{end}条，共{totalCount}条",
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'fid',
                        'value' => //'forums.name',
                        function($model) {
                            return $model->forums->name;   //主要通过此种方式实现
                        },
                        'filter' => Broom::get_forum_id(), //此处我们可以将筛选项组合成key-value形式
                    ],
                    [
                        'attribute' => 'floor',
                        'value' => //'floors.v',
                        function($model) {
                            return $model->floors->v;   //主要通过此种方式实现
                        },
                        'filter' => Broom::get_floor_id(), //此处我们可以将筛选项组合成key-value形式
                    ],
                    'name',
                    'note',
                    [
                        'attribute' => 'stat',
                        'value' => //'colleges.name',
                        function($model) {
                            return $model->Stat;   //主要通过此种方式实现
                        },
                        'filter' => Broom::$List['stat'], //此处我们可以将筛选项组合成key-value形式
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{update} {delete}', //只需要展示删除和更新
                        'buttons' => [
                            'update' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-pencil"></i> 修改', ['forum/broom-update', 'id' => $key], ['class' => 'btn btn-primary btn-xs',]);
                            },
                            'delete' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-trash-o"></i> 删除', ['forum/broom-delete', 'id' => $key], ['class' => 'btn btn-danger btn-xs', 'data' => ['confirm' => '删除房间将会影响相关小室及床位，此操作不能恢复，你确定要删除房间吗？',]]);
                            },
                        ],
                    ],
                ],
            ]);
            ?>
            <?php Pjax::end(); ?>        </div>
    </div>
</div>