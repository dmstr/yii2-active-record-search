<?php
/**
 * @package dmstr\activeRecordSearch
 */

namespace dmstr\activeRecordSearch\controllers\base;

use Yii;
use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;

/**
 * BaseAdminController for the search module.
 * If set, it will check in beforeAction if User has the configured adminPermission for this module
 *
 */
class BaseAdminController extends Controller
{

    public function beforeAction($action)
    {

        if ((!empty($this->module->adminPermission)) && (! Yii::$app->user->can($this->module->adminPermission))) {
            throw new MethodNotAllowedHttpException(Yii::t('search', 'Permission denied.'));
        }

        return parent::beforeAction($action);
    }

}
