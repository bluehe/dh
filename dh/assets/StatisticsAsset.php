<?php

namespace dh\assets;

use yii\web\AssetBundle;

/**
 * Main dh application asset bundle.
 */
class StatisticsAsset extends AssetBundle {

    public $sourcePath = '@vendor/almasaeed2010/adminlte/bower_components'; //路径
    public $css = [
            //'daterangepicker/daterangepicker.css',
    ];
    public $js = [
        'jquery-ui/jquery-ui.min.js',
            //'daterangepicker/moment.min.js',
            //'daterangepicker/daterangepicker.js',
    ];
    public $depends = [
        'dh\assets\CommonAsset', //依赖关系
    ];

}
