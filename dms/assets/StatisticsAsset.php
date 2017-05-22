<?php

namespace dms\assets;

use yii\web\AssetBundle;

/**
 * Main dms application asset bundle.
 */
class IndexAsset extends AssetBundle {

    public $sourcePath = '@vendor/almasaeed2010/adminlte/plugins/jQueryUI'; //路径
    public $css = [
    ];
    public $js = [
        'jquery-ui.min.js',
    ];
    public $depends = [
        'dms\assets\CommonAsset', //依赖关系
    ];

}
