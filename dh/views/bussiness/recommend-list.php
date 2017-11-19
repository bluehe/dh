<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '推荐网址';
$this->params['breadcrumbs'][] = ['label' => '业务中心', 'url' => ['suggest-list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recommend-index">

    <div class="box box-primary">
        <div class="box-body">

            <p>
                <?= Html::a('添加新网址', ['recommend-create'], ['class' => 'btn btn-success']) ?>
            </p>
            <?php Pjax::begin(); ?>                            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{summary}\n<div class=table-responsive>{items}</div>\n{pager}",
                'summary' => "第{begin}-{end}条，共{totalCount}条",
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'label' => '图片',
                        'value' =>
                        function($model) {
                            return Html::img($model->img, ['class' => 'img-rounded', 'width' => 23, 'height' => 23]);
                        },
                        'format' => 'raw',
                    ],
                    'name',
                    [
                        'attribute' => 'url',
                        'value' =>
                        function($model) {
                            return Html::a($model->url, $model->url, ['target' => '_blank']);
                        },
                        'format' => 'raw',
                    ],
                    'click_num',
                    // 'creared_at',
                    // 'update_at',
                    'sort_order',
                    [
                        'attribute' => 'stat',
                        'value' =>
                        function($model) {
                            return $model->Stat;
                        },
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{update} {delete}', //只需要展示删除和更新
                        'buttons' => [
                            'update' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-pencil"></i> 修改', ['recommend-update', 'id' => $key], ['class' => 'btn btn-primary btn-xs',]);
                            },
                            'delete' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-trash-o"></i> 删除', ['recommend-delete', 'id' => $key], ['class' => 'btn btn-danger btn-xs', 'data' => ['confirm' => '确定要删除吗？',]]);
                            },
                        ],
                    ],
                ],
            ]);
            ?>
            <?php Pjax::end(); ?>        </div>
    </div>
</div>