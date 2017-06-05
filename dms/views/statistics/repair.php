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
                <li class="active"><a href="#repair_line" data-toggle="tab">趋势</a></li>
                <li><a href="#repair_area" data-toggle="tab">区域</a></li>
                <li><a href="#repair_type" data-toggle="tab">类型</a></li>
                <li><a href="#repair_evaluate" data-toggle="tab">满意度</a></li>

                <li><a href="#repair_worker" data-toggle="tab">人员</a></li>

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
                <div class="tab-pane active" id="repair_line">
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
                                'text' => '报修趋势统计',
                            ],
                            'xAxis' => [
                                'type' => 'category'
                            ],
                            'yAxis' => [
                                'title' => ['text' => '数量'],
//                                'stackLabels' => [
//                                    'enabled' => true,
//                                    'style' => [
//                                        'fontWeight' => 'bold',
//                                    ]
//                                ]
                            ],
                            'tooltip' => [
                                'shared' => true,
                                'crosshairs' => true
                            ],
                            'plotOptions' => [
                                'line' => [
                                    'dataLabels' => [
                                        'enabled' => true,
                                    ],
                                    'showInLegend' => true,
                                ]
                            ],
                            'series' => $series['day'],
                        ]
                    ]);
                    ?>
                </div>
                <div class="tab-pane row" id="repair_area">
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
                    <section class="col-md-12">
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
                                    'text' => '满意度趋势统计',
                                ],
                                'xAxis' => [
                                    'type' => 'category'
                                ],
                                'yAxis' => [
                                    'title' => ['text' => '满意度'],
                                ],
                                'tooltip' => [
                                    'shared' => true,
                                    'crosshairs' => true
                                ],
                                'plotOptions' => [
                                    'line' => [
                                        'dataLabels' => [
                                            'enabled' => true,
                                        ],
                                        'showInLegend' => true,
                                    ]
                                ],
                                'series' => $series['day_evaluat'],
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

                <div class="tab-pane row" id="repair_worker">
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
                                    'text' => '人员统计',
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
                                            this . series . name + ' : ' + this . y;
                                }")
                                ],
                                'plotOptions' => [
                                    'column' => [
                                        'dataLabels' => ['enabled' => true]
                                    ],
                                ],
                                'series' => $series['work'],
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
                                    'text' => '维修统计',
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
                                'series' => $series['work_repair'],
                            ]
                        ]);
                        ?>
                    </section>
                </div>


            </div>
        </div>
        <?php Pjax::end(); ?>
        <!-- /.box -->
    </div>
</div>
<!-- /.row (main row) -->