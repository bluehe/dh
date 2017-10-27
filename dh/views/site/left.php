<?php

use dh\components\Tab;
use dh\models\Website;
?>
<div class="mk">

    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="col-lg-4 active"><a href="#addlist" aria-controls="addlist" role="tab" data-toggle="tab">添加动态</a></li>
        <li role="presentation"  class="col-lg-4"><a href="#addorder" aria-controls="addorder" role="tab" data-toggle="tab">添加排行</a></li>
        <li role="presentation"  class="col-lg-4"><a href="#clickorder" aria-controls="clickorder" role="tab" data-toggle="tab">点击排行</a></li>

    </ul>

    <!-- Tab panes -->
    <div class="tab-content" id="cxscroll">
        <div role="tabpanel" class="tab-pane box active" id="addlist">
            <div class="list-group list">
                <?=
                Tab::widget(['items' => Website::get_tab_addlist(), 'showImg' => true]
                )
                ?>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="addorder">
            <div class="list-group">
                <?=
                Tab::widget(['items' => Website::get_tab_addorder(), 'showImg' => true]
                )
                ?>

            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="clickorder">
            <div class="list-group">
                <?=
                Tab::widget(['items' => Website::get_tab_clickorder(), 'showImg' => true]
                )
                ?>

            </div>
        </div>

    </div>

</div>
<?php dh\assets\AppAsset::addScript($this, Yii::$app->assetManager->getPublishedUrl('dh/web') . '/js/jquery.cxscroll.min.js') ?>
<script>
<?php $this->beginBlock('left') ?>
    $(function () {
        $("#cxscroll").cxScroll({direction: "bottom", speed: 1000, time: 2000});
    });
<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['left'], \yii\web\View::POS_END); ?>
