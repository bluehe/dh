<?php

namespace dh\assets;

use yii\web\AssetBundle;

/**
 * Main dh application asset bundle.
 */
class Select2Asset extends AssetBundle {

    public $sourcePath = '@vendor/almasaeed2010/adminlte'; //路径
    public $css = [
        'plugins/select2/select2.min.css', //css
    ];
    public $js = [
        'plugins/select2/select2.full.min.js',
    ];
    public $depends = [
    ];

}
