<?php

namespace dms\assets;

use yii\web\AssetBundle;

/**
 * Main dms application asset bundle.
 */
class StatisticsAsset extends AssetBundle {

    public $sourcePath = '@vendor/almasaeed2010/adminlte/plugins'; //路径
    public $css = [
            //'daterangepicker/daterangepicker.css',
    ];
    public $js = [
        'jQueryUI/jquery-ui.min.js',
            //'daterangepicker/moment.min.js',
            //'daterangepicker/daterangepicker.js',
    ];
    public $depends = [
        'dms\assets\CommonAsset', //依赖关系
    ];

}
