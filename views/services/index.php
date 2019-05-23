<?php

use yii\helpers\Html;
use wdmg\services\MainAsset;
use wdmg\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $model wdmg\services\models\Services */

$this->title = Yii::t('app/modules/services', 'Services');
$this->params['breadcrumbs'][] = $this->title;
$bundle = MainAsset::register($this);

?>
<div class="page-header">
    <h1><?= Html::encode($this->title) ?> <small class="text-muted pull-right">[v.<?= $this->context->module->version ?>]</small></h1>
</div>
<div class="services-index">
    <div class="list-group tabs-list">
        <a href="?action=restore&target=chmod" class="col-xs-4 col-sm-3 col-md-2 list-group-item">
            <span class="icon glyphicon glyphicon-wrench"></span>
            <?= Yii::t('app/modules/services', 'Restore directory rights') ?>
            <span class="descr text-primary">0777/0755</span>
        </a>
        <a href="?action=clear&target=runtime" class="col-xs-4 col-sm-3 col-md-2 list-group-item">
            <span class="icon glyphicon glyphicon-fire"></span>
            <?= Yii::t('app/modules/services', 'Clear runtime cache') ?>
            <span class="descr text-primary"><?= $size["runtime"]; ?></span>
        </a>
        <a href="?action=clear&target=assets" class="col-xs-4 col-sm-3 col-md-2 list-group-item">
            <span class="icon glyphicon glyphicon-hdd"></span>
            <?= Yii::t('app/modules/services', 'Clear web cache') ?>
            <span class="descr text-primary"><?= $size["assets"]; ?></span>
        </a>
        <a href="?action=clear&target=cache" class="col-xs-4 col-sm-3 col-md-2 list-group-item">
            <span class="icon glyphicon glyphicon-erase"></span>
            <?= Yii::t('app/modules/services', 'Clear system cache') ?>
            <span class="descr text-primary"><?= $size["cache"]; ?></span>
        </a>
<?php
    if(class_exists('\wdmg\activity\models\Activity') && isset(Yii::$app->modules['activity'])) {
        ?>
        <a href="<?php if ($size["activity"] == 0) echo '#'; else echo '?action=clear&target=activity'; ?>" class="col-xs-4 col-sm-3 col-md-2 list-group-item<?php if ($size["activity"] == 0) echo ' disabled'; ?>">
            <span class="icon glyphicon glyphicon-bell"></span>
            <?= Yii::t('app/modules/services', 'Clear users activity') ?>
            <span class="descr text-primary"><?= StringHelper::integerAmount($size["activity"], 2, true); ?></span>
        </a>
        <?php
    }
?>
<?php
    if(class_exists('\wdmg\stats\models\Visitors') && isset(Yii::$app->modules['stats'])) {
        ?>
        <a href="<?php if ($size["stats"] == 0) echo '#'; else echo '?action=clear&target=stats'; ?>" class="col-xs-4 col-sm-3 col-md-2 list-group-item<?php if ($size["stats"] == 0) echo ' disabled'; ?>">
            <span class="icon glyphicon glyphicon-signal"></span>
            <?= Yii::t('app/modules/services', 'Clear statistics') ?>
            <span class="descr text-primary"><?= StringHelper::integerAmount($size["stats"], 2, true); ?></span>
        </a>
        <?php
    }
?>
<?php
    if(class_exists('\wdmg\users\models\Users') && isset(Yii::$app->modules['users'])) {
        ?>
        <a href="<?php if ($size["users"]["unconfirmed"] == 0) echo '#'; else echo '?action=clear&target=users-unconfirmed'; ?>" class="col-xs-4 col-sm-3 col-md-2 list-group-item<?php if ($size["users"]["unconfirmed"] == 0) echo ' disabled'; ?>">
            <span class="icon glyphicon glyphicon-user"></span>
            <?= Yii::t('app/modules/services', 'Delete unconfirmed users') ?>
            <span class="descr text-primary"><?= StringHelper::integerAmount($size["users"]["unconfirmed"], 2, true); ?></span>
        </a>
        <a href="<?php if ($size["users"]["blocked"] == 0) echo '#'; else echo '?action=clear&target=users-blocked'; ?>" class="col-xs-4 col-sm-3 col-md-2 list-group-item<?php if ($size["users"]["blocked"] == 0) echo ' disabled'; ?>">
            <span class="icon glyphicon glyphicon-user"></span>
            <?= Yii::t('app/modules/services', 'Delete blocked users') ?>
            <span class="descr text-primary"><?= StringHelper::integerAmount($size["users"]["blocked"], 2, true); ?></span>
        </a>
        <?php
    }
?>
    </div>
</div>

<?php echo $this->render('../_debug'); ?>
