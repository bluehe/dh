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
            <ul class="list-group">
                <li class="list-group-item">Cras justo odio</li>
                <li class="list-group-item">Dapibus ac facilisis in</li>
                <li class="list-group-item">Morbi leo risus</li>
                <li class="list-group-item">Porta ac consectetur ac</li>
                <li class="list-group-item">Vestibulum at eros</li>
                <li class="list-group-item">Cras justo odio</li>
                <li class="list-group-item">Dapibus ac facilisis in</li>
                <li class="list-group-item">Morbi leo risus</li>
                <li class="list-group-item">Porta ac consectetur ac</li>

            </ul>
        </div>
        <div role="tabpanel" class="tab-pane" id="clickorder">
            <div class="list-group">
                <a href="#" class="list-group-item">Cras justo odio</a>
                <a href="#" class="list-group-item"><span class="badge">14222</span>Dapibus ac facilisis in</a>
                <a href="#" class="list-group-item"><span class="badge">14</span>Morbi leo risus</a>
                <a href="#" class="list-group-item"><span class="badge">14</span>Porta ac consectetur ac</a>
                <a href="#" class="list-group-item"><span class="badge">14</span>Vestibulum at eros</a>
                <a href="#" class="list-group-item">Vestibulum at eros</a>
                <a href="#" class="list-group-item">Vestibulum at eros</a>
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
