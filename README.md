[![Progress](https://img.shields.io/badge/required-Yii2_v2.0.13-blue.svg)](https://packagist.org/packages/yiisoft/yii2) [![Github all releases](https://img.shields.io/github/downloads/wdmg/yii2-tasks/total.svg)](https://GitHub.com/wdmg/yii2-tasks/releases/) [![GitHub version](https://badge.fury.io/gh/wdmg%2Fyii2-tasks.svg)](https://github.com/wdmg/yii2-tasks) ![Progress](https://img.shields.io/badge/progress-in_development-red.svg) [![GitHub license](https://img.shields.io/github/license/wdmg/yii2-tasks.svg)](https://github.com/wdmg/yii2-tasks/blob/master/LICENSE) 

# Yii2 Services Module
System Service Manager for Yii2

# Requirements 
* PHP 5.6 or higher
* Yii2 v.2.0.20 and newest
* [Yii2 Base](https://github.com/wdmg/yii2-base) module (required)
* [Yii2 Activity](https://github.com/wdmg/yii2-activity) module (support/optionaly)
* [Yii2 Stats](https://github.com/wdmg/yii2-stats) module (support/optionaly)
* [Yii2 Users](https://github.com/wdmg/yii2-users) module (support/optionaly)


# Installation
To install the module, run the following command in the console:

`$ composer require "wdmg/yii2-services"`

After configure db connection, run the following command in the console:

`$ php yii services/init`

And select the operation you want to perform:
  1) Restore directory rights
  2) Clear runtime cache
  3) Clear web cache
  4) Clear the all system cache

# Configure
To add a module to the project, add the following data in your configuration file:

    'modules' => [
        ...
        'services' => [
            'class' => 'wdmg\services\Module',
            'routePrefix' => 'admin'
        ],
        ...
    ],

# Routing
Use the `Module::dashboardNavItems()` method of the module to generate a navigation items list for NavBar, like this:

    <?php
        echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
            'label' => 'Modules',
            'items' => [
                Yii::$app->getModule('services')->dashboardNavItems(),
                ...
            ]
        ]);
    ?>

# Status and version [in progress development]
* v.1.1.7 - Added extra options to composer.json and navbar menu icon
* v.1.1.6 - Added choice param for non interactive mode
* v.1.1.5 - Module refactoring