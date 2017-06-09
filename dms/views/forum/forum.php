<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '楼苑管理';
$this->params['breadcrumbs'][] = ['label' => '楼苑设置', 'url' => ['forum/forum']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="forum-index">
    <div class="box box-primary">
        <div class="box-body">

            <p>
                <?= Html::a('创建楼苑', ['forum/forum-create'], ['class' => 'btn btn-success']) ?>
            </p>
            <?php Pjax::begin(); ?>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{summary}\n<div class=table-responsive>{items}</div>\n{pager}",
                'summary' => '第{begin}-{end}条，共{totalCount}条',
                'tableOptions' => ['class' => 'table table-bordered table-hover'],
                'rowOptions' => function($model) {
                    return ['class' => $model->fup ? '' : 'success'];
                },
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'], //序列号从1自增长
                    // 'id',
                    'name',
//                    [
//                        'attribute' => 'fup',
//                        'value' =>
//                        function($model) {
//                            return $model->fup ? $model->fups->name : $model->fup;   //主要通过此种方式实现
//                        },
//                    //'filter' => Forum::get_forumfup_id(), //此处我们可以将筛选项组合成key-value形式
//                    ],
//                    [
//                        'attribute' => 'mold',
//                        'value' =>
//                        function($model) {
//                            return $model->Mold;   //主要通过此种方式实现
//                        },
//                    ],
                    'sort_order',
                    [
                        'attribute' => 'stat',
                        'value' =>
                        function($model) {
                            return $model->Stat;   //主要通过此种方式实现
                        },
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{update} {delete}', //只需要展示删除和更新
                        'buttons' => [
                            'update' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-pencil"></i> 修改', ['forum/forum-update', 'id' => $key], ['class' => 'btn btn-primary btn-xs',]);
                            },
                            'delete' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-trash-o"></i> 删除', ['forum/forum-delete', 'id' => $key], ['class' => 'btn btn-danger btn-xs', 'data' => ['confirm' => '删除楼苑会一起删除相关的下级楼苑、房间、床位等，此操作不能恢复，你确定要删除楼苑吗？',]]);
                            },
                        ],
                    ],
                ],
            ]);
            ?> <?php Pjax::end(); ?>
        </div>
    </div>
</div>
