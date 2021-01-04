<?php

namespace wdmg\services\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;
use wdmg\services\models\Services;

class InitController extends Controller
{
    /**
     * @inheritdoc
     */
    public $choice = null;

    /**
     * @inheritdoc
     */
    public $defaultAction = 'index';

    public function options($actionID)
    {
        return ['choice', 'color', 'interactive', 'help'];
    }

    public function actionIndex($params = null)
    {
        $model = new Services();
        $cache = Yii::$app->cache->cachePath;
        $asset = Yii::$app->basePath.'/web/assets';
        $runtime = Yii::$app->basePath.'/runtime';

        $module = Yii::$app->controller->module;
        $version = $module->version;
        $welcome =
            '╔════════════════════════════════════════════════╗'. "\n" .
            '║                                                ║'. "\n" .
            '║            SERVICES MODULE, v.'.$version.'            ║'. "\n" .
            '║          by Alexsander Vyshnyvetskyy           ║'. "\n" .
            '║       (c) 2019-2021 W.D.M.Group, Ukraine       ║'. "\n" .
            '║                                                ║'. "\n" .
            '╚════════════════════════════════════════════════╝';
        echo $name = $this->ansiFormat($welcome . "\n\n", Console::FG_GREEN);
        echo "Select the operation you want to perform:\n";
        echo "  1) System: Restore directory rights\n";
        echo "  2) System: Clear runtime cache\n";
        echo "  3) System: Clear web cache\n";
        echo "  4) System: Clear the system cache\n";

        if (class_exists('\wdmg\mailer\models\Mails') && $module->moduleLoaded('mailer')) {
            echo "  5) Mailer: Clear cache\n";
        }

        if (class_exists('\wdmg\search\models\Search') && $module->moduleLoaded('search')) {
            echo "  6) Search: Clear cache\n";
            echo "  7) Search: Drop index\n";
        }

        if ($module->moduleLoaded('rss')) {
            echo "  8) RSS: Clear feed cache\n";
        }

        if ($module->moduleLoaded('turbo')) {
            echo "  9) Yandex.Turbo: Clear cache\n";
        }

        if ($module->moduleLoaded('amp')) {
            echo "  10) Google AMP: Clear cache\n";
        }

        if ($module->moduleLoaded('sitemap')) {
            echo "  11) Sitemap: Clear cache\n";
        }

        if (class_exists('\wdmg\activity\models\Activity') && $module->moduleLoaded('activity')) {
            echo "  12) Activity: Clear users activity log\n";
        }

        if (class_exists('\wdmg\stats\models\Visitors') && $module->moduleLoaded('stats')) {
            echo "  13) Stats: Clear visitors statistics\n";
        }

        if (class_exists('\wdmg\users\models\Users') && $module->moduleLoaded('users')) {
            echo "  14) Users: Deleting unconfirmed users\n";
            echo "  15) Users: Deleting blocked users\n";
        }

        if (class_exists('\wdmg\api\models\API') && $module->moduleLoaded('api')) {
            echo "  16) API: Disable all users\n";
            echo "  17) API: Delete disabled users\n";
            echo "  18) API: Drop all access-token`s\n";
        }

        echo "\nYour choice: ";

        if (!is_null($this->choice))
            $selected = $this->choice;
        else
            $selected = trim(fgets(STDIN));

        if ($selected == "1") {

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

            if (Services::setChmod(Yii::$app->basePath, $writables, 0777))
                echo $this->ansiFormat("Writing rights (0777) successfully installed!\n", Console::FG_GREEN);
            else
                echo $this->ansiFormat("Error setting write permissions (0777).\n", Console::FG_RED);

            if (Services::setChmod(Yii::$app->basePath, $executables, 0755))
                echo $this->ansiFormat("Execution rights (0755) successfully installed!\n", Console::FG_GREEN);
            else
                echo $this->ansiFormat("Error setting permissions to execute (0755).\n", Console::FG_RED);

        } else if ($selected == "2") {

            if (Services::clearDir($runtime))
                echo $this->ansiFormat("Runtime has been successfully cleaned!\n", Console::FG_GREEN);
            else
                echo $this->ansiFormat("Error clearing the runtime cache.\n", Console::FG_RED);

        } else if ($selected == "3") {

            if (Services::clearDir($asset))
                echo $this->ansiFormat("Web-assets have been successfully cleared!\n", Console::FG_GREEN);
            else
                echo $this->ansiFormat("Error clearing the web-assets cache.\n", Console::FG_RED);

        } else if ($selected == "4") {

            if (Yii::$app->cache->flush())
                echo $this->ansiFormat("The system cache has been successfully cleared!\n", Console::FG_GREEN);
            else
                echo $this->ansiFormat("Error clearing the system cache.\n", Console::FG_RED);





        } else if ($selected == "12") {
            if (class_exists('\wdmg\activity\models\Activity') && isset(Yii::$app->modules['activity'])) {
                $activity = new \wdmg\activity\models\Activity();

                if ($activity::deleteAll())
                    echo $this->ansiFormat("Users activity log has been successfully cleaned!\n", Console::FG_GREEN);
                else
                    echo $this->ansiFormat("Error clearing users activity log.\n", Console::FG_RED);

            }
        } else if ($selected == "13") {
            if (class_exists('\wdmg\stats\models\Visitors') && isset(Yii::$app->modules['stats'])) {
                $stats = new \wdmg\stats\models\Visitors();

                if ($stats::deleteAll())
                    echo $this->ansiFormat("Statistics has been successfully cleaned!\n", Console::FG_GREEN);
                else
                    echo $this->ansiFormat("Error clearing statistics.\n", Console::FG_RED);

            }
        } else if ($selected == "14") {
            if (class_exists('\wdmg\users\models\Users') && isset(Yii::$app->modules['users'])) {
                $users = new \wdmg\users\models\Users();
                $removed = $users::find()
                    ->where(['status' => $users::USR_STATUS_WAITING])
                    ->andWhere(
                        'updated_at <= NOW() - INTERVAL 1 DAY'
                    )->all();

                $count = 0;
                foreach ($removed as $remove) {
                    if($remove->delete())
                        $count++;
                }

                if ($count > 0)
                    echo $this->ansiFormat("Unconfirmed users has been successfully deleted!\n", Console::FG_GREEN);
                else
                    echo $this->ansiFormat("Error deleting unconfirmed users.\n", Console::FG_RED);

            }
        } else if ($selected == "15") {
            if (class_exists('\wdmg\users\models\Users') && isset(Yii::$app->modules['users'])) {
                $users = new \wdmg\users\models\Users();
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

                if ($count > 0)
                    echo $this->ansiFormat("Blocked users has been successfully deleted!\n", Console::FG_GREEN);
                else
                    echo $this->ansiFormat("Error deleting blocked users.\n", Console::FG_RED);

            }
        } else {
            echo "\n";
            echo $this->ansiFormat("Error! Your selection has not been recognized.\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        echo "\n";
        return ExitCode::OK;
    }
}
