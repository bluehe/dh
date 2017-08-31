<?php

use dmstr\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
?>
<div class="content-wrapper">

    <section class="content">
        <?= Alert::widget() ?>
        <?= $content ?>
    </section>
</div>

