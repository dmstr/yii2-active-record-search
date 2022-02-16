<?php
/**
 * @package dmstr\activeRecordSearch
 */

namespace dmstr\activeRecordSearch\controllers\api;

use Yii;
use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;

/**
 * BaseApiController for the search module.
 * If set, it will check in beforeAction if User has the configured apiPermission for this module
 *
 */
class BaseApiController extends Controller
{

    public function beforeAction($action)
    {

        if ((empty($this->module->apiPermission)) && (! Yii::$app->user->can($this->module->apiPermission))) {
            throw new MethodNotAllowedHttpException(Yii::t('yii', 'Permission denied.'));
        }

        return parent::beforeAction($action);
    }

}
