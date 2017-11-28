<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use dh\models\System;

if (Yii::$app->controller->id === 'site') {
    dh\assets\AppAsset::register($this);
    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('dh/web');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
        <head>
            <meta charset="<?= Yii::$app->charset ?>">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <?= Html::csrfMetaTags() ?>
            <title><?= $this->title ? Html::encode($this->title) . ' - ' : '' ?><?= System::getValue('system_title') ? System::getValue('system_title') : Yii::$app->name ?></title>
                <meta name="keywords" content="<?= System::getValue('system_keywords') ?>">
            <meta name="description" content="<?= System::getValue('system_desc') ?>">
            <?php $this->head() ?>
        </head>
        <body class="skin-<?= Yii::$app->params['skin'] ?>">
            <?php $this->beginBody() ?>

            <?=
            $this->render(
                    'site-header.php', ['directoryAsset' => $directoryAsset]
            )
            ?>
            <?=
            $this->render(
                    'site-content.php', ['content' => $content, 'directoryAsset' => $directoryAsset]
            )
            ?>
            <?=
            $this->render(
                    'site-footer.php', ['directoryAsset' => $directoryAsset]
            )
            ?>


            <?php $this->endBody() ?>
        </body>
    </html>
    <?php $this->endPage() ?>
    <?php
} else {

    dh\assets\UserAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('dh/web');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
        <head>
            <meta charset="<?= Yii::$app->charset ?>"/>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <?= Html::csrfMetaTags() ?>
            <title><?= Html::encode($this->title) ?> - <?= Yii::$app->name ?></title>
            <?php $this->head() ?>
        </head>
        <body class="hold-transition <?= \dmstr\helpers\AdminLteHelper::skinClass() ?> sidebar-mini">
            <?php $this->beginBody() ?>
            <div class="wrapper">

                <?=
                $this->render(
                        'header.php', ['directoryAsset' => $directoryAsset]
                )
                ?>

                <?=
                $this->render(
                        'left.php', ['directoryAsset' => $directoryAsset]
                )
                ?>

                <?=
                $this->render(
                        'content.php', ['content' => $content, 'directoryAsset' => $directoryAsset]
                )
                ?>

            </div>

            <?php $this->endBody() ?>
        </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
