<?php

namespace dh\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
        'js/jquery.lazyload.min.js',
        'js/site.js',
    ];
    public $depends = [
        'rmrevin\yii\fontawesome\AssetBundle',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

    //定义按需加载JS方法，注意加载顺序在最后
    public static function addScript($view, $jsfile) {
        $view->registerJsFile($jsfile, [AppAsset::className(), 'depends' => 'dh\assets\AppAsset']);
    }

    //定义按需加载css方法，注意加载顺序在最后
    public static function addCss($view, $cssfile) {
        $view->registerCssFile($cssfile, [AppAsset::className(), 'depends' => 'dh\assets\AppAsset']);
    }

    /**
     * @inheritdoc
     */
    public function init() {
        $controller_id = \Yii::$app->controller->id;
        $action_id = \Yii::$app->controller->action->id;
        if ($controller_id == 'site' && $action_id == 'user') {
            $this->depends[] = 'dh\assets\Select2Asset';
        }

        parent::init();
    }

}
