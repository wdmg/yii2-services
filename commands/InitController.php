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
    public $defaultAction = 'index';

    public function actionIndex($params = null)
    {
        $model = new Services();
        $cache = Yii::$app->cache->cachePath;
        $asset = Yii::$app->basePath.'/web/assets';
        $runtime = Yii::$app->basePath.'/runtime';

        $version = Yii::$app->controller->module->version;
        $welcome =
            '╔════════════════════════════════════════════════╗'. "\n" .
            '║                                                ║'. "\n" .
            '║            SERVICES MODULE, v.'.$version.'            ║'. "\n" .
            '║          by Alexsander Vyshnyvetskyy           ║'. "\n" .
            '║         (c) 2019 W.D.M.Group, Ukraine          ║'. "\n" .
            '║                                                ║'. "\n" .
            '╚════════════════════════════════════════════════╝';
        echo $name = $this->ansiFormat($welcome . "\n\n", Console::FG_GREEN);
        echo "Select the operation you want to perform:\n";
        echo "  1) Restore directory rights\n";
        echo "  2) Clear runtime cache\n";
        echo "  3) Clear web cache\n";
        echo "  4) Clear the system cache\n";

        $count = 4;
        if (class_exists('\wdmg\activity\models\Activity') && isset(Yii::$app->modules['activity'])) {
            $count++;
            echo "  ".$count.") Clear users activity\n";
            $triger['activity'] = $count;
        }

        if (class_exists('\wdmg\stats\models\Visitors') && isset(Yii::$app->modules['stats'])) {
            $count++;
            echo "  ".$count.") Clear statistics\n";
            $triger['stats'] = $count;
        }

        if (class_exists('\wdmg\users\models\Users') && isset(Yii::$app->modules['users'])) {
            $count++;
            echo "  ".$count.") Delete unconfirmed users\n";
            $triger['users-unconfirmed'] = $count;

            $count++;
            echo "  ".$count.") Delete blocked users\n";
            $triger['users-blocked'] = $count;
        }

        echo "Your choice: ";

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

            if(Services::setChmod(Yii::$app->basePath, $writables, 0777))
                echo $this->ansiFormat("Writing rights (0777) successfully installed!\n", Console::FG_GREEN);
            else
                echo $this->ansiFormat("Error setting write permissions (0777).\n", Console::FG_RED);

            if(Services::setChmod(Yii::$app->basePath, $executables, 0755))
                echo $this->ansiFormat("Execution rights (0755) successfully installed!\n", Console::FG_GREEN);
            else
                echo $this->ansiFormat("Error setting permissions to execute (0755).\n", Console::FG_RED);

        } else if($selected == "2") {
            if(Services::clearDir($runtime))
                echo $this->ansiFormat("Runtime has been successfully cleaned!\n", Console::FG_GREEN);
            else
                echo $this->ansiFormat("Error clearing the runtime cache.\n", Console::FG_RED);

        } else if($selected == "3") {
            if(Services::clearDir($asset))
                echo $this->ansiFormat("Web-assets have been successfully cleared!\n", Console::FG_GREEN);
            else
                echo $this->ansiFormat("Error clearing the web-assets cache.\n", Console::FG_RED);

        } else if($selected == "4") {
            if(Yii::$app->cache->flush())
                echo $this->ansiFormat("The system cache has been successfully cleared!\n", Console::FG_GREEN);
            else
                echo $this->ansiFormat("Error clearing the system cache.\n", Console::FG_RED);

        } else if($selected == $triger['activity'] && (class_exists('\wdmg\activity\models\Activity') && isset(Yii::$app->modules['activity']))) {
            $activity = new \wdmg\activity\models\Activity();
            if($activity::deleteAll())
                echo $this->ansiFormat("Users activity log has been successfully cleaned!\n", Console::FG_GREEN);
            else
                echo $this->ansiFormat("Error clearing users activity log.\n", Console::FG_RED);

        } else if($selected == $triger['stats'] && (class_exists('\wdmg\stats\models\Visitors') && isset(Yii::$app->modules['stats']))) {
            $stats = new \wdmg\stats\models\Visitors();
            if($stats::deleteAll())
                echo $this->ansiFormat("Statistics has been successfully cleaned!\n", Console::FG_GREEN);
            else
                echo $this->ansiFormat("Error clearing statistics.\n", Console::FG_RED);

        } else if($selected == $triger['users-unconfirmed'] && (class_exists('\wdmg\users\models\Users') && isset(Yii::$app->modules['users']))) {
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
            if($count > 0)
                echo $this->ansiFormat("Unconfirmed users has been successfully deleted!\n", Console::FG_GREEN);
            else
                echo $this->ansiFormat("Error deleting unconfirmed users.\n", Console::FG_RED);

        } else if($selected == $triger['users-blocked'] && (class_exists('\wdmg\users\models\Users') && isset(Yii::$app->modules['users']))) {
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
            if($count > 0)
                echo $this->ansiFormat("Blocked users has been successfully deleted!\n", Console::FG_GREEN);
            else
                echo $this->ansiFormat("Error deleting blocked users.\n", Console::FG_RED);

        } else {
            echo $this->ansiFormat("Error! Your selection has not been recognized.\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        echo "\n";
        return ExitCode::OK;
    }
}
