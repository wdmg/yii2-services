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

        if (class_exists('\wdmg\activity\models\Activity') && $this->module->moduleLoaded('activity')) {
            $activity = new \wdmg\activity\models\Activity();

            if ($action == 'clear' && $target == 'activity') {
                $removed = $activity::deleteAll();
                if ($removed > 0) {
                    $alerts[] = [
                        'type' => 'success',
                        'message' => Yii::t('app/modules/services', 'Users activity log has been successfully cleaned!'),
                    ];
                } else {
                    $alerts[] = [
                        'type' => 'warning',
                        'message' => Yii::t('app/modules/services', 'Error clearing users activity log.'),
                    ];
                }
            }
            $size['activity'] = intval($activity::find()->count());
        }


        if (class_exists('\wdmg\stats\models\Visitors') && $this->module->moduleLoaded('stats')) {
            $stats = new \wdmg\stats\models\Visitors();

            if ($action == 'clear' && $target == 'stats') {
                $removed = $stats::deleteAll();
                if ($removed > 0) {
                    $alerts[] = [
                        'type' => 'success',
                        'message' => Yii::t('app/modules/services', 'Statistics has been successfully cleaned!'),
                    ];
                } else {
                    $alerts[] = [
                        'type' => 'warning',
                        'message' => Yii::t('app/modules/services', 'Error clearing statistics.'),
                    ];
                }
            }
            $size['stats'] = intval($stats::find()->count());
        }

        if (class_exists('\wdmg\users\models\Users') && $this->module->moduleLoaded('users')) {
            $users = new \wdmg\users\models\Users();

            if ($action == 'clear' && $target == 'users-unconfirmed') {
                $removed = $users::find()
                    ->where(['status' => $users::USR_STATUS_WAITING])
                    ->andWhere(
                        'updated_at <= NOW() - INTERVAL 1 DAY'
                    )->all();

                $count = 0;
                foreach ($removed as $remove) {
                    if ($remove->delete())
                        $count++;
                }

                if ($count > 0) {
                    $alerts[] = [
                        'type' => 'success',
                        'message' => Yii::t('app/modules/services', 'Unconfirmed users has been successfully deleted!'),
                    ];
                } else {
                    $alerts[] = [
                        'type' => 'warning',
                        'message' => Yii::t('app/modules/services', 'Error deleting unconfirmed users.'),
                    ];
                }
            }

            if ($action == 'clear' && $target == 'users-blocked') {
                $removed = $users::find()
                    ->where([
                        'or',
                        ['status' => $users::USR_STATUS_DELETED],
                        ['status' => $users::USR_STATUS_BLOCKED]
                    ])->all();

                $count = 0;
                foreach ($removed as $remove) {
                    if($remove->delete())
                        $count++;
                }

                if ($count > 0) {
                    $alerts[] = [
                        'type' => 'success',
                        'message' => Yii::t('app/modules/services', 'Blocked users has been successfully deleted!'),
                    ];
                } else {
                    $alerts[] = [
                        'type' => 'warning',
                        'message' => Yii::t('app/modules/services', 'Error deleting blocked users.'),
                    ];
                }
            }

            $size['users']['unconfirmed'] = intval($users::find()
                ->where(['status' => $users::USR_STATUS_WAITING])
                ->andWhere(
                    'updated_at <= NOW() - INTERVAL 1 DAY'
                )->count());

            $size['users']['blocked'] = intval($users::find()
                ->where([
                    'or',
                    ['status' => $users::USR_STATUS_DELETED],
                    ['status' => $users::USR_STATUS_BLOCKED]
                ])->count());

        }

        if (class_exists('\wdmg\api\models\API') && $this->module->moduleLoaded('api')) {
            $clients = new \wdmg\api\models\API();

            if ($action == 'clear' && $target == 'api-disable') {

            }

            if ($action == 'clear' && $target == 'api-delete') {

            }

            if ($action == 'clear' && $target == 'api-tokens') {

            }

            $size['api']['users'] = intval($clients::find()->count());
            $size['api']['disabled'] = intval($clients::find()->where(['status' => $clients::API_CLIENT_STATUS_DISABLED])->count());
            $size['api']['tokens'] = intval($clients::find()->where(['status' => $clients::API_CLIENT_STATUS_ACTIVE])->count());
        }

        foreach ($alerts as $alert) {
            Yii::$app->getSession()->setFlash($alert['type'], $alert['message']);
        }

        $size['assets'] = $model::formatSize($model::directorySize($asset));
        $size['runtime'] = $model::formatSize($model::directorySize($runtime));
        $size['cache'] = $model::formatSize($model::directorySize($cache));

        if(!empty($action) || !empty($target)) {
            return $this->redirect(['index']);
        } else {
            return $this->render('index', [
                'module' => $this->module,
                'model' => $model,
                'size' => $size
            ]);
        }
    }
}
