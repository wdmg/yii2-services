<?php

use yii\helpers\Html;
use wdmg\services\ServicesAsset;
use wdmg\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $model wdmg\services\models\Services */

$this->title = Yii::t('app/modules/services', 'Services');
$this->params['breadcrumbs'][] = $this->title;
$bundle = ServicesAsset::register($this);

?>
<div class="page-header">
    <h1><?= Html::encode($this->title) ?> <small class="text-muted pull-right">[v.<?= $this->context->module->version ?>]</small></h1>
</div>
<div class="services-index">
    <div class="list-group tabs-list row">
        <div class="col-xs-12" style="padding-bottom:20px;">
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
        </div>
<?php
    if ($intance = $module->moduleLoaded('activity', true)) {
?>
    <div class="col-xs-12" style="padding-bottom:20px;">
        <h4 class="page-title"><?= $intance->name; ?></h4>
        <a href="<?php if ($size["activity"] == 0) echo '#'; else echo '?action=clear&target=activity'; ?>" class="col-xs-4 col-sm-3 col-md-2 list-group-item<?php if ($size["activity"] == 0) echo ' disabled'; ?>">
            <span class="icon glyphicon glyphicon-bell"></span>
            <?= Yii::t('app/modules/services', 'Clear users activity') ?>
            <span class="descr text-primary"><?= StringHelper::integerAmount($size["activity"], 2, true); ?></span>
        </a>
    </div>
<?php
    }
?>
<?php
    if ($intance = $module->moduleLoaded('mailer', true)) {
?>
    <div class="col-xs-12" style="padding-bottom:20px;">
        <h4 class="page-title"><?= $intance->name; ?></h4>
        <a href="<?php if ($size["activity"] == 0) echo '#'; else echo '?action=clear&target=mailer'; ?>" class="col-xs-4 col-sm-3 col-md-2 list-group-item<?php if ($size["mailer"] == 0) echo ' disabled'; ?>">
            <span class="icon glyphicon glyphicon-envelope"></span>
            <?= Yii::t('app/modules/services', 'Clear mail cache') ?>
            <span class="descr text-primary"><?= StringHelper::integerAmount($size["mailer"], 2, true); ?></span>
        </a>
    </div>
<?php
    }
?>



<?php
    if ($intance = $module->moduleLoaded('search', true)) {
?>
    <div class="col-xs-12" style="padding-bottom:20px;">
        <h4 class="page-title"><?= $intance->name; ?></h4>
        <a href="<?php if ($size["live-search"] == 0) echo '#'; else echo '?action=clear&target=live-search'; ?>" class="col-xs-4 col-sm-3 col-md-2 list-group-item<?php if ($size["live-search"] == 0) echo ' disabled'; ?>">
            <span class="icon fa fa-fw fa-search"></span>
            <?= Yii::t('app/modules/services', 'Clear Live Search cache') ?>
            <span class="descr text-primary"><?= StringHelper::integerAmount($size["live-search"], 2, true); ?></span>
        </a>
        <a href="<?php if ($size["search-index"] == 0) echo '#'; else echo '?action=drop&target=search-index'; ?>" class="col-xs-4 col-sm-3 col-md-2 list-group-item<?php if ($size["search-index"] == 0) echo ' disabled'; ?>">
            <span class="icon fa fa-fw fa-search"></span>
            <?= Yii::t('app/modules/services', 'Drop Search index') ?>
            <span class="descr text-primary"><?= StringHelper::integerAmount($size["search-index"], 2, true); ?></span>
        </a>
    </div>
<?php
    }
?>
<?php
    if ($intance = $module->moduleLoaded('rss', true)) {
?>
    <div class="col-xs-12" style="padding-bottom:20px;">
        <h4 class="page-title"><?= $intance->name; ?></h4>
        <a href="<?php if ($size["rss"] == 0) echo '#'; else echo '?action=clear&target=rss'; ?>" class="col-xs-4 col-sm-3 col-md-2 list-group-item<?php if ($size["rss"] == 0) echo ' disabled'; ?>">
            <span class="icon fa fa-fw fa-rss"></span>
            <?= Yii::t('app/modules/services', 'Clear RSS-feed cache') ?>
            <span class="descr text-primary"><?= StringHelper::integerAmount($size["rss"], 2, true); ?></span>
        </a>
    </div>
<?php
    }
?>
<?php
    if ($intance = $module->moduleLoaded('turbo', true)) {
?>
    <div class="col-xs-12" style="padding-bottom:20px;">
        <h4 class="page-title"><?= $intance->name; ?></h4>
        <a href="<?php if ($size["turbo"] == 0) echo '#'; else echo '?action=clear&target=turbo'; ?>" class="col-xs-4 col-sm-3 col-md-2 list-group-item<?php if ($size["turbo"] == 0) echo ' disabled'; ?>">
            <span class="icon fa fa-fw fa-rocket"></span>
            <?= Yii::t('app/modules/services', 'Clear Yandex.Turbo cache') ?>
            <span class="descr text-primary"><?= StringHelper::integerAmount($size["turbo"], 2, true); ?></span>
        </a>
    </div>
<?php
    }
?>
<?php
    if ($intance = $module->moduleLoaded('amp', true)) {
?>
    <div class="col-xs-12" style="padding-bottom:20px;">
        <h4 class="page-title"><?= $intance->name; ?></h4>
        <a href="<?php if ($size["amp"] == 0) echo '#'; else echo '?action=clear&target=amp'; ?>" class="col-xs-4 col-sm-3 col-md-2 list-group-item<?php if ($size["amp"] == 0) echo ' disabled'; ?>">
            <span class="icon fa fa-fw fa-bolt"></span>
            <?= Yii::t('app/modules/services', 'Clear Google AMP cache') ?>
            <span class="descr text-primary"><?= StringHelper::integerAmount($size["amp"], 2, true); ?></span>
        </a>
    </div>
<?php
    }
?>
<?php
    if ($intance = $module->moduleLoaded('sitemap', true)) {
?>
    <div class="col-xs-12" style="padding-bottom:20px;">
        <h4 class="page-title"><?= $intance->name; ?></h4>
        <a href="<?php if ($size["sitemap"] == 0) echo '#'; else echo '?action=clear&target=sitemap'; ?>" class="col-xs-4 col-sm-3 col-md-2 list-group-item<?php if ($size["sitemap"] == 0) echo ' disabled'; ?>">
            <span class="icon fa fa-fw fa-sitemap"></span>
            <?= Yii::t('app/modules/services', 'Clear Sitemap cache') ?>
            <span class="descr text-primary"><?= StringHelper::integerAmount($size["sitemap"], 2, true); ?></span>
        </a>
    </div>
<?php
    }
?>

<?php
    if ($intance = $module->moduleLoaded('stats', true)) {
?>
    <div class="col-xs-12" style="padding-bottom:20px;">
        <h4 class="page-title"><?= $intance->name; ?></h4>
        <a href="<?php if ($size["stats"] == 0) echo '#'; else echo '?action=clear&target=stats'; ?>" class="col-xs-4 col-sm-3 col-md-2 list-group-item<?php if ($size["stats"] == 0) echo ' disabled'; ?>">
            <span class="icon glyphicon glyphicon-signal"></span>
            <?= Yii::t('app/modules/services', 'Clear statistics') ?>
            <span class="descr text-primary"><?= StringHelper::integerAmount($size["stats"], 2, true); ?></span>
        </a>
    </div>
<?php
    }
?>
<?php
    if ($intance = $module->moduleLoaded('users', true)) {
?>
    <div class="col-xs-12" style="padding-bottom:20px;">
        <h4 class="page-title"><?= $intance->name; ?></h4>
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
    </div>
<?php
    }
?>
<?php
    if ($intance = $module->moduleLoaded('api', true)) {
?>
    <div class="col-xs-12" style="padding-bottom:20px;">
        <h4 class="page-title"><?= $intance->name; ?></h4>
        <a href="<?php if ($size["api"]["users"] == 0) echo '#'; else echo '?action=clear&target=api-disable'; ?>" class="col-xs-4 col-sm-3 col-md-2 list-group-item<?php if ($size["api"]["users"] == 0) echo ' disabled'; ?>">
            <span class="icon glyphicon glyphicon-user"></span>
            <?= Yii::t('app/modules/services', 'Disable all users') ?>
            <span class="descr text-primary"><?= StringHelper::integerAmount($size["api"]["users"], 2, true); ?></span>
        </a>
        <a href="<?php if ($size["api"]["users"] == 0) echo '#'; else echo '?action=clear&target=api-delete'; ?>" class="col-xs-4 col-sm-3 col-md-2 list-group-item<?php if ($size["api"]["users"] == 0) echo ' disabled'; ?>">
            <span class="icon glyphicon glyphicon-user"></span>
            <?= Yii::t('app/modules/services', 'Delete disabled users') ?>
            <span class="descr text-primary"><?= StringHelper::integerAmount($size["api"]["disabled"], 2, true); ?></span>
        </a>
        <a href="<?php if ($size["api"]["tokens"] == 0) echo '#'; else echo '?action=clear&target=api-tokens'; ?>" class="col-xs-4 col-sm-3 col-md-2 list-group-item<?php if ($size["api"]["tokens"] == 0) echo ' disabled'; ?>">
            <span class="icon glyphicon glyphicon-user"></span>
            <?= Yii::t('app/modules/services', 'Drop all access-token`s') ?>
            <span class="descr text-primary"><?= StringHelper::integerAmount($size["api"]["tokens"], 2, true); ?></span>
        </a>
    </div>
<?php
    }
?>
    </div>
</div>

<?php echo $this->render('../_debug'); ?>
