<?php

namespace wdmg\services;
use yii\web\AssetBundle;

class ServicesAsset extends AssetBundle
{
    public $sourcePath = '@vendor/wdmg/yii2-services/assets';
    public $css = [
        'css/services.css',
    ];

    public function init()
    {
        parent::init();
    }
}

?>