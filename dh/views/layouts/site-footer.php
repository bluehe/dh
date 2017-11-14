<?php
/* @var $this \yii\web\View */
/* @var $content string */
$system = Yii::$app->cache->get('system_info');
?>

<footer class="footer">
    <div class="main-footer">
        <div class="container">

            <div class="tongji row">
                <div class="col-lg-3 col-xs-6 text-center">本站共享 <b><?= Yii::$app->params['statistics']['category0'] ?></b> 个分类</div>
                <div class="col-lg-3 col-xs-6 text-center">本站共享 <b><?= Yii::$app->params['statistics']['website0'] ?></b> 个网址</div>
                <div class="col-lg-3 col-xs-6 text-center">用户收藏 <b><?= Yii::$app->params['statistics']['category1'] ?></b> 个分类</div>
                <div class="col-lg-3 col-xs-6 text-center">用户收藏 <b><?= Yii::$app->params['statistics']['website1'] ?></b> 个网址</div>
            </div>

            <div class="text-center copyright">
                <div>&copy; <?= date('Y', time()) ?> <a href="<?= Yii::$app->homeUrl ?>"><?= Yii::$app->name ?>.</a>  All rights reserved.</div>
                <div>
                    <a href = "http://www.miibeian.gov.cn/" target = "_blank"><?= $system['system_icp']; ?></a>
                    <?= $system['system_statcode']; ?>
                </div>
            </div>

        </div>
    </div>
</footer>
<script>
<?php $this->beginBlock('lazy') ?>
    //lazyload();
    $("img.lazyload").lazyload({
        threshold: 500
    });
    <?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['lazy'], \yii\web\View::POS_END); ?>
<script>
<?php $this->beginBlock('baidu')
?>
//    (function () {
//        var bp = document.createElement('script');
//        var curProtocol = window.location.protocol.split(':')[0];
//        if (curProtocol === 'https') {
//            bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
//        } else {
//            bp.src = 'http://push.zhanzhang.baidu.com/push.js';
//        }
//        var s = document.getElementsByTagName("script")[0];
//        s.parentNode.insertBefore(bp, s);
//    })();
<?php $this->endBlock() ?>
</script>
<?php //$this->registerJs($this->blocks['baidu'], \yii\web\View::POS_END);  ?>

