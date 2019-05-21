<?php

use yii\helpers\Html;
use wdmg\services\MainAsset;

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
            <?= Yii::t('app/modules/services', 'Clear the system cache') ?>
            <span class="descr text-primary"><?= $size["cache"]; ?></span>
        </a>
    </div>
</div>

<?php echo $this->render('../_debug'); ?>
