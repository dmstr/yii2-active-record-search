<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\activeRecordSearch;

use dmstr\activeRecordSearch\widgets\SearchInput;
use Yii;

/**
 * Class Module
 * @package dmstr\activeRecordSearch
 * Author: Jens Giessmann <j.giessmann@herzogkommunikation.de>
 */
class Module extends \yii\base\Module {

    public static $moduleId = 'search';
    /**
     * callback function that can be used to check if search module is enabled in current context
     * @var callable|null
     */
    public $enableCallback = null;
    /**
     * default route for this module
     * @var string
     */
    public $defaultRoute = 'frontend';
    /**
     * if set, we add this as theme pathMap in FrontendController
     * useful to overwrite just the index.php view for the frontendController of this module
     * @var bool
     */
    public $frontendViewPath = false;
    /**
     * layout that should be used in frontendController
     * @var string|null
     */
    public $frontendLayout = '@frontend/views/layouts/main';
    /**
     * permission that will be checked in beforeAction within frontendController
     * @var string
     */
    public $frontendPermission  = 'search_frontend';
    /**
     * permission that will be checked in beforeAction within all "admin" controllers of this module
     * @var string
     */
    public $adminPermission  = 'search_admin';
    /**
     * permission that will be checked in beforeAction within all api rest controllers
     */
    public $apiPermission = 'search_api';
    /**
     * class that should be used as input widget in frontenController index.php view
     * @var string
     */
    public $searchInputWidget = SearchInput::class;

    /**
     * this method wil be called from SearchInput widget to check if search
     * module is "enabled" in current context.
     *
     * @return bool|mixed
     */
    public static function isEnabled()
    {
        // if module is initialised
        /** @var \yii\base\Module $class */
        $class = self::class;
        $module = $class::getInstance();
        if ($module && is_callable($module->enableCallback)) {
            return call_user_func($module->enableCallback);
        }
        // if module is defined but not initialised yet
        if (!empty(Yii::$app->modules[self::$moduleId])
            && is_array(Yii::$app->modules[self::$moduleId])
            && isset(Yii::$app->modules[self::$moduleId]['enableCallback'])
            && is_callable(Yii::$app->modules[self::$moduleId]['enableCallback'])) {
            return call_user_func(Yii::$app->modules[self::$moduleId]['enableCallback']);
        }

        return true;
    }

}
