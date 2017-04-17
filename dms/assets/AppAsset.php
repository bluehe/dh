<?php

namespace dms\assets;

use yii\web\AssetBundle;

/**
 * Main dms application asset bundle.
 */
class AppAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
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
        if ($controller_id == 'site' && $action_id == 'index') {
            $this->depends[] = 'dms\assets\IndexAsset';
        } elseif ($controller_id == 'forum' && ($action_id == 'room-create' || $action_id == 'room-update' || $action_id == 'bed-create' || $action_id == 'bed-update')) {
            $this->depends[] = 'dms\assets\Select2Asset';
        } else {
            $this->depends[] = 'dms\assets\CommonAsset';
        }

        parent::init();
    }

}
