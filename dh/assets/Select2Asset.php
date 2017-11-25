<?php

namespace dh\assets;

use yii\web\AssetBundle;

/**
 * Main dh application asset bundle.
 */
class Select2Asset extends AssetBundle {

    public $sourcePath = '@vendor/almasaeed2010/adminlte'; //路径
    public $css = [
        'bower_components/select2/dist/css/select2.min.css', //css
    ];
    public $js = [
        'bower_components/select2/dist/js/select2.full.min.js',
        'bower_components/jquery-ui/jquery-ui.min.js',
    ];
    public $depends = [
    ];

}
