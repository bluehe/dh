<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use dms\models\Major;

/* @var $this yii\web\View */
/* @var $searchModel dms\models\MajorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '专业设置';
$this->params['breadcrumbs'][] = ['label' => '参数设置', 'url' => ['common/college']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="major-index">

    <div class="box box-primary">
        <div class="box-body">
            <?php // echo $this->render('_search', ['model' => $searchModel]);    ?>

            <p>
                <?= Html::a('创建专业', ['common/major-create'], ['class' => 'btn btn-success']) ?>
            </p>
            <?php Pjax::begin(); ?>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'layout' => "{summary}\n<div class=table-responsive>{items}</div>\n{pager}",
                'summary' => '第{begin}-{end}条，共{totalCount}条',
                'columns' => [
                    'id',
                    [
                        'attribute' => 'college',
                        'value' => //'colleges.name',
                        function($model) {
                            return $model->colleges->name;   //主要通过此种方式实现
                        },
                        'filter' => Major::get_major_college(), //此处我们可以将筛选项组合成key-value形式
                    ],
                    'name',
                    'sort_order',
                    ['class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{update} {delete}', //只需要展示删除和更新
                        'buttons' => [
                            'update' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-pencil"></i> 修改', ['common/major-update', 'id' => $key], ['class' => 'btn btn-primary btn-xs',]);
                            },
                            'delete' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-trash-o"></i> 删除', ['common/major-delete', 'id' => $key], ['class' => 'btn btn-danger btn-xs', 'data' => ['confirm' => '删除专业将会影响相关教师及学生，此操作不能恢复，你确定要删除专业吗？',]]);
                            },
                        ],
                    ],
                ],
            ]);
            ?>
            <?php Pjax::end(); ?>        </div>
    </div>
</div>