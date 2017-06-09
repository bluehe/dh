<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel dms\models\CheckOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Check Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="check-order-index">

    <div class="box box-primary">
        <div class="box-body">
                                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
            
            <p>
                <?= Html::a('Create Check Order', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
            <?php Pjax::begin(); ?>                            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{summary}\n<div class=table-responsive>{items}</div>\n{pager}",
                'summary' => "第{begin}-{end}条，共{totalCount}条",
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                'filterModel' => $searchModel,
        'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                            'id',
            'related_table',
            'related_id',
            'bids',
            'note',
            // 'created_at',
            // 'updated_at',
            // 'checkout_at',
            // 'created_uid',
            // 'updated_uid',
            // 'checkout_uid',
            // 'stat',


                ['class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{update} {delete}', //只需要展示删除和更新
                'buttons' => [
                'update' => function($url, $model, $key) {
                return Html::a('<i class="fa fa-pencil"></i> 修改', ['update', 'id' => $key], ['class' => 'btn btn-primary btn-xs',]);
                },
                'delete' => function($url, $model, $key) {
                return Html::a('<i class="fa fa-trash-o"></i> 删除', ['delete', 'id' => $key], ['class' => 'btn btn-danger btn-xs', 'data' => ['confirm' => '删除专业将会影响相关教师及学生，此操作不能恢复，你确定要删除专业吗？',]]);
                },
                ],
                ],
                ],
                ]); ?>
                        <?php Pjax::end(); ?>        </div>
    </div>
</div>