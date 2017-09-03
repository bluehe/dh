<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<footer class="footer">
    <div class="main-footer">
        <div class="container">

            <div class="tongji">
                <div class="col-lg-3 col-xs-6 text-center">本站收录 <b>271</b> 个分类</div>
                <div class="col-lg-3 col-xs-6 text-center">本站收录 <b>3197</b> 个网址</div>
                <div class="col-lg-3 col-xs-6 text-center">用户添加 <b>7795</b> 个分类</div>
                <div class="col-lg-3 col-xs-6 text-center">用户添加 <b>56427</b> 个网址</div>
            </div>



            <div class="copy mt-10 text-c"> Copyright2016-2017&nbsp;&nbsp;<a href="/">云导航</a>&nbsp;&nbsp; All Right Reserved&nbsp;&nbsp;易采通（常州）供应链管理有限公司&nbsp;&nbsp;<a href="http://www.miibeian.gov.cn/" target="_blank">苏ICP备15048548号-8</a><span> </span></div>

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