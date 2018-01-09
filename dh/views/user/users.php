<?php

use yii\helpers\Html;
use yii\grid\GridView;
use dh\models\User;
use dh\models\UserLevel;
use dh\models\Website;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel dh\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户管理';
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['users']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">

    <div class="box box-primary">
        <div class="box-body">
            <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>



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
                            return Html::img($model->avatar ? $model->avatar : '@web/image/user.png', ['class' => 'img-rounded', 'width' => 23, 'height' => 23]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'username',
                        'format' => 'raw',
                        'value' =>
                        function($model, $key) {

                            return Html::a($model->username, ['/site/people', 'id' => $key], ['target' => '_blank']);
                        },
                        'headerOptions' => ['width' => '60'],
                    ],
                    'nickname',
                    'email',
                    'tel',
                    // 'plate',
                    // 'skin',
                    [
                        'label' => '网址',
                        'value' =>
                        function($model) {

                            return Website::get_website_num($model->id, '', Website::STAT_OPEN);
                        },
                        'headerOptions' => ['width' => '50'],
                    ],
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
                        'attribute' => 'last_login',
                        'value' =>
                        function($model) {
                            return $model->last_login > 0 ? date('Y-m-d H:i:s', $model->last_login) : '';
                        },
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
                        'headerOptions' => ['width' => '235'],
                    ],
                    [
                        'attribute' => 'status',
                        'value' =>
                        function($model, $key) {
                            return Html::a($model->Status, ['user/users-change', 'id' => $key], ['class' => 'btn btn-xs ' . ($model->status == User::STATUS_ACTIVE ? 'btn-success' : 'btn-danger')]);
                        },
                        'format' => 'raw',
                        'filter' => User::$List['status'],
                        'headerOptions' => ['width' => '80'],
                    ],
                    // 'updated_at',
                    ['class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{update}', //只需要展示删除和更新
                        'buttons' => [
                            'update' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-pencil"></i> 修改', ['users-update', 'id' => $key], ['class' => 'btn btn-primary btn-xs',]);
                            },
                        ],
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</div>