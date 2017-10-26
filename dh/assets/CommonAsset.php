<?php

namespace dh\assets;

use yii\web\AssetBundle;

/**
 * Main dms application asset bundle.
 */
class CommonAsset extends AssetBundle {

    public $sourcePath = '@vendor/almasaeed2010/adminlte/plugins'; //路径
    public $css = [
//        'pace/pace.min.css',
    ];
    public $js = [
//        'pace/pace.min.js',
        'slimScroll/jquery.slimscroll.min.js',
        'fastclick/fastclick.js',
    ];
    public $depends = [
        'dmstr\web\AdminLteAsset', //依赖关系
    ];

}
