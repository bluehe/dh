<?php

namespace dms\assets;

use yii\web\AssetBundle;

/**
 * Main dms application asset bundle.
 */
class CalendarAsset extends AssetBundle {

    public $sourcePath = '@vendor/almasaeed2010/adminlte/plugins/fullcalendar'; //路径
    public $css = [
        'fullcalendar.min.css', //css
    ];
    public $js = [
        'https://code.jquery.com/ui/1.11.4/jquery-ui.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js',
        'fullcalendar.min.js',
    ];
    public $depends = [
        'dms\assets\CommonAsset', //依赖关系
    ];

}
