<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = '首页';
?>
<div class="container">

    <?php if (count($recommend) > 0) { ?>
        <div class="row recommend">
            <?php foreach ($recommend as $r) { ?>
                <div class="col-lg-1 col-xs-3 text-center">
                    <?= Html::a(Html::img('data/recommend/' . $r['img'], ['alt' => $r['name'], 'class' => 'img-rounded']) . '<span>' . $r['name'] . '</span>', $r['url'], ['data-id' => $r['id']]) ?>

                </div>
                <?php
            }
            ?>
        </div>
    <?php } ?>
</div>
