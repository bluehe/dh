<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use dh\models\User;
use dh\models\UserLevel;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel dh\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户管理';
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['user']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <div class="box box-primary">
        <div class="box-body">
            <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>


            <?php Pjax::begin(); ?>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{summary}\n<div class=table-responsive>{items}</div>\n{pager}",
                'summary' => "第{begin}-{end}条，共{totalCount}条",
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'id',
                        'value' =>
                        function($model) {
                            return $model->id;
                        },
                        'headerOptions' => ['width' => '60'],
                    ],
                    [
                        'label' => '头像',
                        'value' =>
                        function($model) {
                            return Html::img($model->avatar ? $model->avatar : '@web/image/user.png', ['class' => 'img-rounded', 'width' => 30, 'height' => 30]);
                        },
                        'format' => 'raw',
                    ],
                    'username',
                    'nickname',
                    'email',
                    'tel',
                    // 'plate',
                    // 'skin',
                    [
                        'label' => '等级',
                        'format' => 'raw',
                        'value' =>
                        function($model) {
                            $level = UserLevel::get_user_level($model->id);
                            return Html::tag('span', 'Lv.' . $level, ['class' => 'badge icon_level_c' . ceil($level / Yii::$app->params['level_c'])]);
                        },
                        'headerOptions' => ['width' => '60'],
                    ],
                    [
                        'attribute' => 'point',
                        'value' =>
                        function($model) {

                            return $model->point;
                        },
                        'headerOptions' => ['width' => '80'],
                    ],
                    [
                        'attribute' => 'created_at',
                        'value' =>
                        function($model) {
                            return date('Y-m-d H:i:s', $model->created_at);   //主要通过此种方式实现
                        },
                        'filter' => DateRangePicker::widget([
                            'name' => 'UserSearch[created_at]',
                            'useWithAddon' => true,
                            'presetDropdown' => true,
                            'convertFormat' => true,
                            'value' => Yii::$app->request->get('UserSearch')['created_at'],
                            'pluginOptions' => [
                                'timePicker' => false,
                                'locale' => [
                                    'format' => 'Y-m-d',
                                    'separator' => '至'
                                ],
                                'linkedCalendars' => false,
                            ],
                        ]),
                        'headerOptions' => ['width' => '220'],
                    ],
                    [
                        'attribute' => 'status',
                        'value' =>
                        function($model, $key) {
                            return Html::a($model->Status, ['user/user-change', 'id' => $key], ['class' => 'btn btn-xs ' . ($model->status == User::STATUS_ACTIVE ? 'btn-success' : 'btn-danger')]);
                        },
                        'format' => 'raw',
                        'filter' => User::$List['status'],
                        'headerOptions' => ['width' => '80'],
                    ],
                    // 'updated_at',
                    ['class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{update} {delete}', //只需要展示删除和更新
                        'buttons' => [
                            'update' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-pencil"></i> 修改', ['update', 'id' => $key], ['class' => 'btn btn-primary btn-xs',]);
                            },
                            'delete' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-trash-o"></i> 删除', ['delete', 'id' => $key], ['class' => 'btn btn-danger btn-xs', 'data' => ['confirm' => '你确定要删除吗？',]]);
                            },
                        ],
                    ],
                ],
            ]);
            ?>
            <?php Pjax::end(); ?>        </div>
    </div>
</div>
<script>
<?php $this->beginBlock('user') ?>

    //状态切换
    $('.user-index').on('click', '.change', function () {
        var key = $(this).parents('tr').data('key');
        $.getJSON("<?= Url::toRoute('ajax-user/change-user-stat') ?>", function (data) {
            if (data.stat === 'success') {
                $('.user-sign').addClass('btn-default').attr('disabled', 'disabled').html('已签到').removeClass('btn-primary user-sign');
                my_alert('success', data.msg, 3000);
            } else if (data.stat === 'fail') {
                my_alert('danger', data.msg, 3000);
            }
        });

    });

<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['user'], \yii\web\View::POS_END); ?>