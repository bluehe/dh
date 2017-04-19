<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '类型管理';
$this->params['breadcrumbs'][] = ['label' => '维修设置', 'url' => ['repair/unit']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="type-index">

    <div class="box box-primary">
        <div class="box-body">

            <p>
                <?= Html::a('添加类型', ['repair/type-create'], ['class' => 'btn btn-success']) ?>
            </p>
            <?php Pjax::begin(); ?>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{summary}\n<div class=table-responsive>{items}</div>\n{pager}",
                'summary' => '第{begin}-{end}条，共{totalCount}条',
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    ['label' => '类型', 'attribute' => 'v'],
                    'sort_order',
                    ['class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{update} {delete}', //只需要展示删除和更新
                        'buttons' => [
                            'update' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-pencil"></i> 修改', ['repair/type-update', 'id' => $key], ['class' => 'btn btn-primary btn-xs',]);
                            },
                            'delete' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-trash-o"></i> 删除', ['repair/type-delete', 'id' => $key], ['class' => 'btn btn-danger btn-xs', 'data' => ['confirm' => '删除类型将会影响相关的报修信息，此操作不能恢复，你确定要删除类型吗？',]]);
                            },
                        ],
                    ],
                ],
            ]);
            ?> <?php Pjax::end(); ?>
        </div>
    </div>
</div>