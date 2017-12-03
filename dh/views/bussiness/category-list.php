<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use dh\models\Category;

/* @var $this yii\web\View */
/* @var $searchModel dh\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '分类管理';
$this->params['breadcrumbs'][] = ['label' => '业务中心', 'url' => ['suggest-list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <div class="box box-primary">
        <div class="box-body">


            <p>
                <?= Html::a('添加分类', ['category-create'], ['class' => 'btn btn-success']) ?>
            </p>
            <?php Pjax::begin(); ?>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{summary}\n<div class=table-responsive>{items}</div>\n{pager}",
                'summary' => "第{begin}-{end}条，共{totalCount}条",
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'uid',
                        'value' =>
                        function($model) {
                            return $model->uid ? $model->u->username : '';
                        },
                    ],
//                    'cid',
                    'title',
                    'collect_num',
                    [
                        'attribute' => 'is_open',
                        'value' =>
                        function($model) {
                            return Html::tag('span', $model->IsOpen, ['class' => ($model->is_open == Category::ISOPEN_OPEN ? 'text-green' : 'text-yellow')]);
                        },
                        'format' => 'raw',
                        'filter' => Category::$List['is_open'],
                    ],
                    [
                        'attribute' => 'stat',
                        'value' =>
                        function($model) {
                            return Html::tag('span', $model->Stat, ['class' => ($model->stat == Category::STAT_OPEN ? 'text-green' : 'text-red')]);
                        },
                        'format' => 'raw',
                        'filter' => Category::$List['stat'],
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{update} {delete}', //只需要展示删除和更新
                        'buttons' => [
                            'update' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-pencil"></i> 修改', ['category-update', 'id' => $key], ['class' => 'btn btn-primary btn-xs',]);
                            },
                            'delete' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-trash-o"></i> 删除', ['category-delete', 'id' => $key], ['class' => 'btn btn-danger btn-xs', 'data' => ['confirm' => '确定删除吗？',]]);
                            },
                        ],
                    ],
                ],
            ]);
            ?>
            <?php Pjax::end(); ?>        </div>
    </div>
</div>