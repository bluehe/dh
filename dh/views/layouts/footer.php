<?php
/* @var $this \yii\web\View */
/* @var $content string */
?>

<footer class="footer">
    <div class="main-footer">
        <div class="container">

            <div class="tongji">
                <div class="col-lg-3 col-xs-6 text-center">本站分享 <b><?= Yii::$app->params['statistics']['category0'] ?></b> 个分类</div>
                <div class="col-lg-3 col-xs-6 text-center">本站分享 <b><?= Yii::$app->params['statistics']['website0'] ?></b> 个网址</div>
                <div class="col-lg-3 col-xs-6 text-center">用户收藏 <b><?= Yii::$app->params['statistics']['category1'] ?></b> 个分类</div>
                <div class="col-lg-3 col-xs-6 text-center">用户收藏 <b><?= Yii::$app->params['statistics']['website1'] ?></b> 个网址</div>
            </div>

            <div class="text-center copyright"> Copyright2016-2017&nbsp;&nbsp;<a href="/">网址收藏夹</a>&nbsp;&nbsp; All Right Reserved&nbsp;&nbsp;<a href="http://www.miibeian.gov.cn/" target="_blank"></a></div>

        </div>
    </div>
</footer>
<script>
<?php $this->beginBlock('baidu') ?>
    (function () {
        var bp = document.createElement('script');
        var curProtocol = window.location.protocol.split(':')[0];
        if (curProtocol === 'https') {
            bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
        } else {
            bp.src = 'http://push.zhanzhang.baidu.com/push.js';
        }
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(bp, s);
    })();
<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['baidu'], \yii\web\View::POS_END); ?>

