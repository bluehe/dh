<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use dh\models\Website;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '我的网址';
$this->params['breadcrumbs'][] = ['label' => '个人中心', 'url' => ['website']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="website-index">

    <div class="box box-primary">
        <div class="box-body">

            <?php Pjax::begin(); ?>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{summary}\n<div class=table-responsive>{items}</div>\n{pager}",
                'summary' => "第{begin}-{end}条，共{totalCount}条",
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'cid',
                        'value' =>
                        function($model) {
                            return $model->c->title;
                        },
                    ],
                    'title',
                    [
                        'attribute' => 'url',
                        'value' =>
                        function($model) {
                            return Html::a($model->url, $model->url, ['target' => '_blank']);
                        },
                        'format' => 'raw',
                    ],
                    // 'share_status',
                    // 'share_id',
                    'collect_num',
                    'click_num',
                    // 'created_at',
                    // 'updated_at',
                    [
                        'attribute' => 'is_open',
                        'value' =>
                        function($model) {
                            return Html::tag('span', $model->IsOpen, ['class' => ($model->is_open == Website::ISOPEN_OPEN ? 'text-green' : 'text-yellow')]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'stat',
                        'value' =>
                        function($model) {
                            return Html::tag('span', $model->Stat, ['class' => ($model->stat == Website::STAT_OPEN ? 'text-green' : ($model->stat == Website::STAT_CLOSE ? 'text-yellow' : 'text-red') )]);
                        },
                        'format' => 'raw',
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{recovery} {delete}',
                        'buttons' => [
                            'recovery' => function($url, $model, $key) {
                                if ($model->stat === Website::STAT_CLOSE && Website::get_category_website_num($model->cid, Website::STAT_OPEN) < 10) {
                                    return Html::a('<i class="fa fa-recycle"></i> 显示', ['website-recovery', 'id' => $key], ['class' => 'btn btn-primary btn-xs']);
                                }
                            },
                            'delete' => function($url, $model, $key) {
                                if ($model->stat !== Website::STAT_OPEN) {
                                    return Html::a('<i class="fa fa-trash-o"></i> 删除', ['website-delete', 'id' => $key], ['class' => 'btn btn-danger btn-xs', 'data' => ['confirm' => '确定要删除吗？',]]);
                                }
                            },
                        ],
                    ],
                ],
            ]);
            ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

<script>
<?php $this->beginBlock('view') ?>
    $('.suggest-index').on('click', '.view', function () {
        $('.modal-title').html('建议反馈');
        $('.modal-body').html('');
        $.get('<?= Url::toRoute('suggest-view') ?>', {id: $(this).closest('tr').data('key')},
                function (data) {
                    $('.modal-body').html(data);
                }
        );
    });

<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['view'], \yii\web\View::POS_END); ?>