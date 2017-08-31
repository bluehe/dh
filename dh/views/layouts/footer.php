<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<footer class="footer">
    <div class="container">

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