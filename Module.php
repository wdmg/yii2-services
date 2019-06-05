<?php

namespace wdmg\services;

/**
 * Yii2 Services
 *
 * @category        Module
 * @version         1.1.4
 * @author          Alexsander Vyshnyvetskyy <alex.vyshnyvetskyy@gmail.com>
 * @link            https://github.com/wdmg/yii2-tasks
 * @copyright       Copyright (c) 2019 W.D.M.Group, Ukraine
 * @license         https://opensource.org/licenses/MIT Massachusetts Institute of Technology (MIT) License
 *
 */

use Yii;
use wdmg\base\BaseModule;

/**
 * Services module definition class
 */
class Module extends BaseModule
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'wdmg\services\controllers';

    /**
     * {@inheritdoc}
     */
    public $defaultRoute = "services/index";

    /**
     * @var string, the name of module
     */
    public $name = "Service";

    /**
     * @var string, the description of module
     */
    public $description = "System Service Manager";

    /**
     * @var string the module version
     */
    private $version = "1.1.4";

    /**
     * @var integer, priority of initialization
     */
    private $priority = 9;

    /**
     * Build dashboard navigation items for NavBar
     * @return object or null
     */
    public function moduleLoaded($id, $returnInstance = false)
    {
        $parent = $this->module->id;
        if ($parent)
            $id = $parent . '/' . $id;

        if (Yii::$app->hasModule($id)) {
            if($returnInstance)
                return Yii::$app->getModule($id);
            else
                return true;
        } else {
            return false;
        }
        return null;
    }

    public function bootstrap($app)
    {
        parent::bootstrap($app);
    }
}