<?php

namespace dmstr\activeRecordSearch\commands;

use dmstr\activeRecordSearch\components\SearchIndexer;
use Yii;
use yii\console\Controller;
use yii\log\Logger;

/**
 * Search IndexController
 * @package dmstr\activeRecordSearch\commands
 * Author: Jens Giessmann <j.giessmann@herzogkommunikation.de>
 */
class IndexController extends Controller
{

    /**
     * Update search index
     */
    public function actionUpdate()
    {

        ini_set('max_execution_time', 0);
        $logger = Yii::getLogger();
        /** @var SearchIndexer Yii::$app->searchIndexer */
        $logger->log('Start search index update', Logger::LEVEL_INFO);
        Yii::$app->searchIndexer->update();
        $logger->log('Finished search index update', Logger::LEVEL_INFO);
    }
}
