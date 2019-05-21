<?php

namespace wdmg\services\controllers;

use Yii;
use wdmg\services\models\Services;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ServicesController implements the CRUD actions for Tasks model.
 */
class ServicesController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public $defaultAction = 'index';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'roles' => ['admin'],
                        'allow' => true
                    ],
                ],
            ],
        ];

        // If auth manager not configured use default access control
        if(!Yii::$app->authManager) {
            $behaviors['access'] = [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'roles' => ['@'],
                        'allow' => true
                    ],
                ]
            ];
        }

        return $behaviors;
    }


    /**
     * Lists all services.
     * @return mixed
     */
    public function actionIndex()
    {
        $alerts = array();
        $model = new Services();

        // Get action and target
        $action = trim(Yii::$app->request->get('action'));
        $target = trim(Yii::$app->request->get('target'));

        // Get path for caches
        $cache = Yii::$app->cache->cachePath;
        $asset = Yii::$app->basePath.'/web/assets';
        $runtime = Yii::$app->basePath.'/runtime';

        if($action == 'restore' && $target == 'chmod')
        {
            if(YII_ENV_DEV) {
                $writables = [
                    'runtime',
                    'web/assets',
                ];
                $executables = [
                    'yii',
                    'tests/bin/yii',
                ];
            } else {
                $writables = [
                    'runtime',
                    'web/assets',
                ];
                $executables = [
                    'yii',
                ];
            }

            if($model::setChmod(Yii::$app->basePath, $writables, 0777)) {
                $alerts[] = [
                    'type' => 'success',
                    'message' => Yii::t('app/modules/services', 'Writing rights (0777) successfully installed!'),
                ];
            } else {
                $alerts[] = [
                    'type' => 'warning',
                    'message' => Yii::t('app/modules/services', 'Error setting write permissions (0777).'),
                ];
            }

            if($model::setChmod(Yii::$app->basePath, $executables, 0755)) {
                $alerts[] = [
                    'type' => 'success',
                    'message' => Yii::t('app/modules/services', 'Execution rights (0755) successfully installed!'),
                ];
            } else {
                $alerts[] = [
                    'type' => 'warning',
                    'message' => Yii::t('app/modules/services', 'Error setting permissions to execute (0755).'),
                ];
            }
        }

        if($action == 'clear' && $target == 'cache')
        {
            if(Yii::$app->cache->flush()) {
                $alerts[] = [
                    'type' => 'success',
                    'message' => Yii::t('app/modules/services', 'The system cache has been successfully cleared!'),
                ];
            } else {
                $alerts[] = [
                    'type' => 'warning',
                    'message' => Yii::t('app/modules/services', 'Error clearing the system cache.'),
                ];
            }
        } else if($action == 'clear' && $target == 'assets') {
            if($model::clearDir($asset)) {
                $alerts[] = [
                    'type' => 'success',
                    'message' => Yii::t('app/modules/services', 'Web-assets have been successfully cleared!'),
                ];
            } else {
                $alerts[] = [
                    'type' => 'warning',
                    'message' => Yii::t('app/modules/services', 'Error clearing the web-assets cache.'),
                ];
            }
        } else if($action == 'clear' && $target == 'runtime') {
            if($model::clearDir($runtime)) {
                $alerts[] = [
                    'type' => 'success',
                    'message' => Yii::t('app/modules/services', 'Runtime has been successfully cleaned!'),
                ];
            } else {
                $alerts[] = [
                    'type' => 'warning',
                    'message' => Yii::t('app/modules/services', 'Error clearing runtime cache.'),
                ];
            }
        }

        foreach ($alerts as $alert) {
            Yii::$app->getSession()->setFlash($alert['type'], $alert['message']);
        }

        // Calculate sizies of chache
        $size['assets'] = $model::formatSize($model::directorySize($asset));
        $size['runtime'] = $model::formatSize($model::directorySize($runtime));
        $size['cache'] = $model::formatSize($model::directorySize($cache));

        return $this->render('index', [
            'model' => $model,
            'size' => $size
        ]);
    }

}
