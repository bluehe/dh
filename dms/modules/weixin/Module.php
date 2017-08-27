<?php

namespace dms\modules\weixin;

/**
 * weixin module definition class
 */
class Module extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'dms\modules\weixin\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        \Yii::$app->user->enableSession = false;
        // custom initialization code goes here
    }

}
