<?php

namespace wdmg\services\models;

use yii\base\Model;

class Services extends Model
{
    public $action;
    public $target;

    public function rules()
    {
        return [
            [['action', 'target'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'action' => Yii::t('app/modules/services', 'Action'),
            'target' => Yii::t('app/modules/services', 'Target'),
        ];
    }
}

?>