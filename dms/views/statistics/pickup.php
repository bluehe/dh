<?php
/* @var $this yii\web\View */

use yii\widgets\Pjax;
use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use kartik\daterange\DateRangePicker;

$this->title = '拾物统计';
$this->params['breadcrumbs'][] = ['label' => '数据统计', 'url' => ['pickup']];
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
                <li class="active"><a href="#pickup_line" data-toggle="tab">发布趋势</a></li>
                <li><a href="#pickup_type" data-toggle="tab">类型</a></li>


                <li class="pull-right header">
<!--                    <button type="button" class="btn btn-default pull-right" id="daterange-btn"><span><i class="fa fa-calendar"></i> 时间选择</span><i class="fa fa-caret-down"></i></button>-->
                    <?=
                    DateRangePicker::widget([
                        'name' => 'daterange',
                        'useWithAddon' => true,
                        'presetDropdown' => true,
                        'convertFormat' => true,
                        'value' => date('Y-m-d', $start) . '至' . date('Y-m-d', $end),
                        'pluginOptions' => [
                            'timePicker' => false,
                            'locale' => [
                                'format' => 'Y-m-d',
                                'separator' => '至'
                            ],
                            'linkedCalendars' => false,
                        ],
                        'pluginEvents' => [
                            "apply.daterangepicker" => "function(start,end,label) {var v=$('.range-value').html(); self.location='/statistics/pickup?range='+v;}",
                        ]
                    ]);
                    ?>
                </li>
            </ul>
            <div class="tab-content no-padding">
                <div class="tab-pane active" id="pickup_line">
                    <?=
                    Highcharts::widget([
                        'scripts' => [
                            'highcharts-more',
                            'modules/exporting',
                            'themes/grid-light'
                        ],
                        'options' => [
                            'lang' => [
                                'printChart' => "打印图表",
                                'downloadJPEG' => "下载JPEG 图片",
                                'downloadPDF' => "下载PDF文档",
                                'downloadPNG' => "下载PNG 图片",
                                'downloadSVG' => "下载SVG 矢量图",
                                'exportButtonTitle' => "导出图片"
                            ],
                            'credits' => ['enabled' => true, 'text' => Yii::$app->request->hostInfo, 'href' => Yii::$app->request->hostInfo],
                            'title' => [
                                'text' => '发布趋势统计',
                            ],
                            'xAxis' => [
                                'type' => 'category'
                            ],
                            'yAxis' => [
                                'title' => ['text' => '数量'],
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
                <div class="tab-pane row" id="pickup_type">
                    <section class="col-md-6">
                        <?=
                        Highcharts::widget([
                            'scripts' => [
                                'highcharts-more',
                                'modules/exporting',
                                'themes/grid-light'
                            ],
                            'options' => [
                                'lang' => [
                                    'printChart' => "打印图表",
                                    'downloadJPEG' => "下载JPEG 图片",
                                    'downloadPDF' => "下载PDF文档",
                                    'downloadPNG' => "下载PNG 图片",
                                    'downloadSVG' => "下载SVG 矢量图",
                                    'exportButtonTitle' => "导出图片"
                                ],
                                'credits' => ['enabled' => true, 'text' => Yii::$app->request->hostInfo, 'href' => Yii::$app->request->hostInfo],
                                'title' => [
                                    'text' => '类型统计',
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
                    <section class="col-md-6">
                        <?=
                        Highcharts::widget([
                            'scripts' => [
                                'highcharts-more',
                                'modules/exporting',
                                'themes/grid-light'
                            ],
                            'options' => [
                                'lang' => [
                                    'printChart' => "打印图表",
                                    'downloadJPEG' => "下载JPEG 图片",
                                    'downloadPDF' => "下载PDF文档",
                                    'downloadPNG' => "下载PNG 图片",
                                    'downloadSVG' => "下载SVG 矢量图",
                                    'exportButtonTitle' => "导出图片"
                                ],
                                'credits' => ['enabled' => true, 'text' => Yii::$app->request->hostInfo, 'href' => Yii::$app->request->hostInfo],
                                'title' => [
                                    'text' => '类型百分比统计',
                                ],
                                'tooltip' => [
                                    'formatter' => new JsExpression("function () {return '<b>' + this.point.name + '</b><br/>' +
                                            this.series.name + ' : ' + this.y;
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
            </div>
        </div>
        <?php Pjax::end(); ?>
        <!-- /.box -->
    </div>
</div>
<!-- /.row (main row) -->
