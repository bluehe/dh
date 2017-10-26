<?php

namespace dh\assets;

use yii\web\AssetBundle;

/**
 * Main dms application asset bundle.
 */
class UserAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    //定义按需加载JS方法，注意加载顺序在最后
    public static function addScript($view, $jsfile) {
        $view->registerJsFile($jsfile, [AppAsset::className(), 'depends' => 'dms\assets\AppAsset']);
    }

    //定义按需加载css方法，注意加载顺序在最后
    public static function addCss($view, $cssfile) {
        $view->registerCssFile($cssfile, [AppAsset::className(), 'depends' => 'dms\assets\AppAsset']);
    }

    /**
     * @inheritdoc
     */
    public function init() {
        $controller_id = \Yii::$app->controller->id;
        $action_id = \Yii::$app->controller->action->id;
        if ($controller_id == 'user' && $action_id == 'index') {
            $this->depends[] = 'dh\assets\IndexAsset';
        } elseif ($controller_id == 'statistics') {
            $this->depends[] = 'dh\assets\StatisticsAsset';
        } else {
            $this->depends[] = 'dh\assets\CommonAsset';
        }

        parent::init();
    }

}
