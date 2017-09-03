<?php
/* @var $this \yii\web\View */
/* @var $content string */

use dh\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('dh/web');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>

        <?=
        $this->render(
                'header.php', ['directoryAsset' => $directoryAsset]
        )
        ?>
        <?=
        $this->render(
                'content.php', ['content' => $content, 'directoryAsset' => $directoryAsset]
        )
        ?>
        <?=
        $this->render(
                'footer.php', ['directoryAsset' => $directoryAsset]
        )
        ?>


        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>

