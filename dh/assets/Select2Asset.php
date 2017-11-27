<?php

namespace dh\assets;

use yii\web\AssetBundle;

/**
 * Main dh application asset bundle.
 */
class Select2Asset extends AssetBundle {

    public $sourcePath = '@vendor/almasaeed2010/adminlte/bower_components'; //路径
    public $css = [
        'select2/dist/css/select2.min.css', //css
    ];
    public $js = [
        'select2/dist/js/select2.full.min.js',
        'jquery-ui/jquery-ui.min.js',
    ];
    public $depends = [
    ];

}
