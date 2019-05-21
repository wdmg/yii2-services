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
        echo "  4) Clear the system cache\n\n";
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
        } else {
            echo $this->ansiFormat("Error! Your selection has not been recognized.\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        echo "\n";
        return ExitCode::OK;
    }
}
