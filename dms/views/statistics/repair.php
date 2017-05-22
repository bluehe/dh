<?php
/* @var $this yii\web\View */

use yii\widgets\Pjax;
use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use kartik\daterange\DateRangePicker;

$this->title = '报修统计';
$this->params['breadcrumbs'][] = ['label' => '数据统计', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- Main row -->
<div class="row">
    <!-- Left col -->
    <div class="col-md-12">
        <?php Pjax::begin(); ?>
        <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs">
                <li class="active"><a href="#repair_area" data-toggle="tab">区域</a></li>
                <li><a href="#repair_type" data-toggle="tab">类型</a></li>
                <li><a href="#repair_evaluate" data-toggle="tab">评价</a></li>
                <li><a href="#repair_worker" data-toggle="tab">人员</a></li>
                <li><a href="#repair_time" data-toggle="tab">时间</a></li>
                <li class="pull-right header">
<!--                    <button type="button" class="btn btn-default pull-right" id="daterange-btn"><span><i class="fa fa-calendar"></i> 时间选择</span><i class="fa fa-caret-down"></i></button>-->
                    <?=
                    DateRangePicker::widget([
                        'name' => 'daterange',
                        'useWithAddon' => true,
                        'presetDropdown' => true,
                        'convertFormat' => true,
                        'value' => date('Y-m-d', $start) . '至' . date('Y-m-d', $end),
//                        'startAttribute' => 'from_date',
//                        'endAttribute' => 'to_date',
//                        'startInputOptions' => ['value' => '2017-06-11'],
//                        'endInputOptions' => ['value' => '2017-07-20'],
                        'pluginOptions' => [
                            'timePicker' => false,
                            'locale' => [
                                'format' => 'Y-m-d',
                                'separator' => '至'
                            ],
                            'linkedCalendars' => false,
                        ],
                        'pluginEvents' => [
                            "apply.daterangepicker" => "function(start,end,label) {var v=$('.range-value').html(); self.location='/statistics/repair?range='+v;}",
                        ]
                    ]);
                    ?>
                </li>
            </ul>
            <div class="tab-content no-padding">
                <div class="tab-pane active row" id="repair_area">
                    <section class="col-md-8">
                        <?=
                        Highcharts::widget([
                            'options' => [
                                'scripts' => [
                                    'highcharts-more',
                                    'modules/exporting',
                                    'themes/grid-light'
                                ],
                                'credits' => ['enabled' => true, 'text' => Yii::$app->request->hostInfo, 'href' => Yii::$app->request->hostInfo],
                                'title' => [
                                    'text' => '报修区域数量统计',
                                ],
                                'xAxis' => [
                                    'type' => 'category'
                                ],
                                'yAxis' => [
                                    'title' => ['text' => '数量'],
                                    'stackLabels' => [
                                        'enabled' => true,
                                        'style' => [
                                            'fontWeight' => 'bold',
                                        ]
                                    ]
                                ],
                                'tooltip' => [
                                    'formatter' => new JsExpression("function () {return '<b>' + this .point.name + '</b><br/>' +
                                            this . series . name + ' : ' + this . y+'<br/>合计 : '+this.point.stackTotal;
                                }")
                                ],
                                'plotOptions' => [
                                    'column' => [
                                        'stacking' => 'normal',
                                        'dataLabels' => ['enabled' => true]
                                    ],
                                ],
                                'series' => $series['area'],
                            ]
                        ]);
                        ?>
                    </section>
                    <section class="col-md-4">
                        <?=
                        Highcharts::widget([
                            'options' => [
                                'scripts' => [
                                    'highcharts-more',
                                    'modules/exporting',
                                    'themes/grid-light'
                                ],
                                'credits' => ['enabled' => true, 'text' => Yii::$app->request->hostInfo, 'href' => Yii::$app->request->hostInfo],
                                'title' => [
                                    'text' => '报修区域百分比统计',
                                ],
                                'tooltip' => [
                                    'formatter' => new JsExpression("function () {return '<b>' + this .point.name + '</b><br/>' +
                                            this . series . name + ' : ' + this . y;
                                }")
                                ],
                                'plotOptions' => [
                                    'pie' => [
                                        'allowPointSelect' => true,
                                        'cursor' => 'pointer',
                                        'dataLabels' => [
                                            'enabled' => true,
                                            'format' => '<b>{point.name}</b>: {point.percentage:.1f} %',
                                        ],
                                        'showInLegend' => true,
                                    ]
                                ],
                                'series' => $series['area_total'],
                            ]
                        ]);
                        ?>
                    </section>
                </div>
                <div class="tab-pane row" id="repair_type">
                    <section class="col-md-8">
                        <?=
                        Highcharts::widget([
                            'scripts' => [
                                'highcharts-more',
                                'modules/exporting',
                                'themes/grid-light'
                            ],
                            'options' => [
                                'credits' => ['enabled' => true, 'text' => Yii::$app->request->hostInfo, 'href' => Yii::$app->request->hostInfo],
                                'title' => [
                                    'text' => '报修类型数量统计',
                                ],
                                'xAxis' => [
                                    'type' => 'category'
                                ],
                                'yAxis' => [
                                    'title' => ['text' => '数量'],
                                    'stackLabels' => [
                                        'enabled' => true,
                                        'style' => [
                                            'fontWeight' => 'bold',
                                        ]
                                    ]
                                ],
                                'tooltip' => [
                                    'formatter' => new JsExpression("function () {return '<b>' + this .point.name + '</b><br/>' +
                                            this . series . name + ' : ' + this . y+'<br/>合计 : '+this.point.stackTotal;
                                }")
                                ],
                                'plotOptions' => [
                                    'column' => [
                                        'stacking' => 'normal',
                                        'dataLabels' => ['enabled' => true]
                                    ],
                                    'pie' => [
                                        'allowPointSelect' => true,
                                        'cursor' => 'pointer',
                                        'dataLabels' => [
                                            'enabled' => false
                                        ],
                                        'showInLegend' => true,
                                    ]
                                ],
                                'series' => $series['type'],
                            ]
                        ]);
                        ?>
                    </section>
                    <section class="col-md-4">
                        <?=
                        Highcharts::widget([
                            'options' => [
                                'scripts' => [
                                    'highcharts-more',
                                    'modules/exporting',
                                    'themes/grid-light'
                                ],
                                'credits' => ['enabled' => true, 'text' => Yii::$app->request->hostInfo, 'href' => Yii::$app->request->hostInfo],
                                'title' => [
                                    'text' => '报修类型百分比统计',
                                ],
                                'tooltip' => [
                                    'formatter' => new JsExpression("function () {return '<b>' + this .point.name + '</b><br/>' +
                                            this . series . name + ' : ' + this . y;
                                }")
                                ],
                                'plotOptions' => [
                                    'pie' => [
                                        'allowPointSelect' => true,
                                        'cursor' => 'pointer',
                                        'dataLabels' => [
                                            'enabled' => true,
                                            'format' => '<b>{point.name}</b>: {point.percentage:.1f} %',
                                        ],
                                        'showInLegend' => true,
                                    ]
                                ],
                                'series' => $series['type_total'],
                            ]
                        ]);
                        ?>
                    </section>
                </div>
                <div class="tab-pane row" id="repair_evaluate">

                    <section class="col-md-4">
                        <?=
                        Highcharts::widget([
                            'options' => [
                                'scripts' => [
                                    'highcharts-more',
                                    'modules/exporting',
                                    'themes/grid-light'
                                ],
                                'credits' => ['enabled' => true, 'text' => Yii::$app->request->hostInfo, 'href' => Yii::$app->request->hostInfo],
                                'title' => [
                                    'text' => '报修评价统计（' . $model->getAttributeLabel('evaluate1') . '）',
                                ],
                                'tooltip' => [
                                    'formatter' => new JsExpression("function () {return '<b>' + this .point.name + '</b><br/>' +
                                            this . series . name + ' : ' + this . y;
                                }")
                                ],
                                'plotOptions' => [
                                    'pie' => [
                                        'allowPointSelect' => true,
                                        'cursor' => 'pointer',
                                        'dataLabels' => [
                                            'enabled' => true,
                                            'format' => '<b>{point.name}</b>: {point.percentage:.1f} %',
                                        ],
                                        'showInLegend' => true,
                                    ]
                                ],
                                'series' => $series['evaluate1'],
                            ]
                        ]);
                        ?>
                    </section>
                    <section class="col-md-4">
                        <?=
                        Highcharts::widget([
                            'options' => [
                                'scripts' => [
                                    'highcharts-more',
                                    'modules/exporting',
                                    'themes/grid-light'
                                ],
                                'credits' => ['enabled' => true, 'text' => Yii::$app->request->hostInfo, 'href' => Yii::$app->request->hostInfo],
                                'title' => [
                                    'text' => '报修评价统计（' . $model->getAttributeLabel('evaluate2') . '）',
                                ],
                                'tooltip' => [
                                    'formatter' => new JsExpression("function () {return '<b>' + this .point.name + '</b><br/>' +
                                            this . series . name + ' : ' + this . y;
                                }")
                                ],
                                'plotOptions' => [
                                    'pie' => [
                                        'allowPointSelect' => true,
                                        'cursor' => 'pointer',
                                        'dataLabels' => [
                                            'enabled' => true,
                                            'format' => '<b>{point.name}</b>: {point.percentage:.1f} %',
                                        ],
                                        'showInLegend' => true,
                                    ]
                                ],
                                'series' => $series['evaluate2'],
                            ]
                        ]);
                        ?>
                    </section>
                    <section class="col-md-4">
                        <?=
                        Highcharts::widget([
                            'options' => [
                                'scripts' => [
                                    'highcharts-more',
                                    'modules/exporting',
                                    'themes/grid-light'
                                ],
                                'credits' => ['enabled' => true, 'text' => Yii::$app->request->hostInfo, 'href' => Yii::$app->request->hostInfo],
                                'title' => [
                                    'text' => '报修评价统计（' . $model->getAttributeLabel('evaluate3') . '）',
                                ],
                                'tooltip' => [
                                    'formatter' => new JsExpression("function () {return '<b>' + this .point.name + '</b><br/>' +
                                            this . series . name + ' : ' + this . y;
                                }")
                                ],
                                'plotOptions' => [
                                    'pie' => [
                                        'allowPointSelect' => true,
                                        'cursor' => 'pointer',
                                        'dataLabels' => [
                                            'enabled' => true,
                                            'format' => '<b>{point.name}</b>: {point.percentage:.1f} %',
                                        ],
                                        'showInLegend' => true,
                                    ]
                                ],
                                'series' => $series['evaluate3'],
                            ]
                        ]);
                        ?>
                    </section>
                </div>
                <div class="tab-pane" id="repair_worker">图表</div>
                <div class="tab-pane" id="repair_time">线图</div>
            </div>
        </div>
        <?php Pjax::end(); ?>
        <!-- /.box -->
    </div>
</div>
<!-- /.row (main row) -->
<script>
<?php $this->beginBlock('index') ?>
    $(".connectedSortable").sortable({
        placeholder: "sort-highlight",
        connectWith: ".connectedSortable",
        handle: ".box-header, .nav-tabs",
        forcePlaceholderSize: true,
        zIndex: 999999
    });
    $(".connectedSortable .box-header, .connectedSortable .nav-tabs-custom").css("cursor", "move");
    //jQuery UI sortable for the todo list
    $(".todo-list").sortable({
        placeholder: "sort-highlight",
        handle: ".handle",
        forcePlaceholderSize: true,
        zIndex: 999999
    });
<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['index'], \yii\web\View::POS_END); ?>
