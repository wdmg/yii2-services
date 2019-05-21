<?php

namespace wdmg\services;
use yii\web\AssetBundle;

class MainAsset extends AssetBundle
{
    public $sourcePath = '@app/vendor/wdmg/yii2-services/assets';
    public $css = [
        'css/services.css',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public function init()
    {
        parent::init();
    }

}

?>