<?php

namespace wdmg\services\models;

use yii\base\Model;
use yii\helpers\FileHelper;

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


    public static function directorySize($path)
    {
        $bytestotal = 0;
        $path = realpath($path);
        if($path !== false) {
            foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS)) as $object) {
                $bytestotal += $object->getSize();
            }
        }
        return $bytestotal;
    }

    public static function formatSize($size)
    {
        $mod = 1024;
        $units = explode(' ', 'bytes Kb Mb Gb Tb Pb');
        for ($i = 0; $size > $mod; $i++) {
            $size /= $mod;
        }
        return round($size, 2).' '.$units[$i];
    }

    public static function clearDir($path)
    {
        $isOk = true;
        $to_removed = [];
        $all_dirs = glob($path . '/*' , GLOB_ONLYDIR);

        foreach ($all_dirs as $dir) {
            $to_removed[$dir] = filemtime($dir);
        }
        asort($to_removed);

        if (count($to_removed) > 0) {
            foreach ($to_removed as $remove => $timestamp) {
                if(FileHelper::removeDirectory($remove) !== NULL) {
                    $isOk = false;
                    Yii::$app->getSession()->setFlash(
                        'warning',
                        Yii::t('app/modules/services', 'Folder {filename} could not be deleted.', [
                            'filename' => $remove
                        ])
                    );
                }
            }
        }
        return $isOk;
    }

    public static function setChmod($root, $paths, $permissions = 0644)
    {
        $isOk = true;
        foreach ($paths as $path) {
            if(!@chmod($root.'/'.$path, $permissions))
                $isOk = false;
        }
        return $isOk;
    }
}

?>