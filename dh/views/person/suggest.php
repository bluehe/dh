<?php

use yii\helpers\Html;
use yii\grid\GridView;
use dh\models\Suggest;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '建议反馈';
$this->params['breadcrumbs'][] = ['label' => '个人中心', 'url' => ['suggest']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="suggest-index">

    <div class="box box-primary">
        <div class="box-body">


            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{summary}\n<div class=table-responsive>{items}</div>\n{pager}",
                'summary' => "第{begin}-{end}条，共{totalCount}条",
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'created_at:datetime',
                    'content:ntext',
                    // 'reply_uid',
                    [
                        'attribute' => 'updated_at',
                        'value' =>
                        function($model) {
                            return $model->updated_at ? date('Y-m-d H:i:s', $model->updated_at) : '';
                        },
                    ],
                    'reply_content:ntext',
                    [
                        'attribute' => 'stat',
                        'value' =>
                        function($model) {
                            return Html::tag('span', $model->Stat, ['class' => 'btn btn-xs disabled ' . ($model->stat == Suggest::STAT_OPEN ? 'btn-success' : 'btn-danger')]);
                        },
                        'format' => 'raw',
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</div>