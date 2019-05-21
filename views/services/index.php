<?php

use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model wdmg\services\models\Services */

$this->title = Yii::t('app/modules/tasks', 'Services');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="page-header">
    <h1><?= Html::encode($this->title) ?> <small class="text-muted pull-right">[v.<?= $this->context->module->version ?>]</small></h1>
</div>
<div class="services-index">
    <?php Pjax::begin(); ?>
    ...
    <?php Pjax::end(); ?>
</div>

<?php echo $this->render('../_debug'); ?>
