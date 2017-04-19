<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel dms\models\RepairUnitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '单位管理';
$this->params['breadcrumbs'][] = ['label' => '维修设置', 'url' => ['repair/unit']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="repair-unit-index">

    <div class="box box-primary">
        <div class="box-body">


            <p>
                <?= Html::a('添加单位', ['repair/unit-create'], ['class' => 'btn btn-success']) ?>
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
                    'name',
                    'company',
                    'tel',
                    'email:email',
                    'address',
                    'note',
                    [
                        'attribute' => 'stat',
                        'value' =>
                        function($model) {
                            return $model->Stat;   //主要通过此种方式实现
                        },
                        'filter' => \dms\models\RepairUnit::$List['stat'], //此处我们可以将筛选项组合成key-value形式
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{update} {delete}', //只需要展示删除和更新
                        'buttons' => [
                            'update' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-pencil"></i> 修改', ['repair/unit-update', 'id' => $key], ['class' => 'btn btn-primary btn-xs',]);
                            },
                            'delete' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-trash-o"></i> 删除', ['repair/unit-delete', 'id' => $key], ['class' => 'btn btn-danger btn-xs', 'data' => ['confirm' => '删除单位将会影响相关报修记录，此操作不能恢复，你确定要删除单位吗？',]]);
                            },
                        ],
                    ],
                ],
            ]);
            ?>
            <?php Pjax::end(); ?>        </div>
    </div>
</div>