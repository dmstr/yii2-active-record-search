<?php
/**
 * /app/src/../runtime/giiant/49eb2de82346bc30092f584268252ed2
 *
 * @package default
 */


namespace dmstr\activeRecordSearch\controllers;

use dmstr\activeRecordSearch\models\FrontendSearch;
use dmstr\activeRecordSearch\Module;
use Yii;
use yii\base\Theme;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;

/**
 * This is the class for controller "FrontendController".
 */
class FrontendController extends Controller
{

    public function beforeAction($action)
    {

        if ((!empty($this->module->frontendPermission)) && (! Yii::$app->user->can($this->module->frontendPermission))) {
            throw new MethodNotAllowedHttpException(Yii::t('search', 'Permission denied.'));
        }

        // set layout from modul param frontendLayout
        if (!empty($this->module->frontendLayout)) {
            $this->layout = $this->module->frontendLayout;
        }
        // if modul param frontendViewPath is set, add this as theme pathMap
        if (!empty($this->module->frontendViewPath) && $this->module->frontendViewPath !== $this->getViewPath()) {
            $pathMap = [
                $this->getViewPath() => [
                    $this->module->frontendViewPath,
                    $this->getViewPath(),
                ],
            ];
            if (\Yii::$app->view->theme) {
                \Yii::$app->view->theme->pathMap = ArrayHelper::merge(\Yii::$app->view->theme->pathMap, $pathMap);
            } else {
                \Yii::$app->view->theme = \Yii::createObject(
                    [
                        'class'   => Theme::class,
                        'pathMap' => $pathMap,
                    ]
                );
            }

        }

        return parent::beforeAction($action);
    }

    public function actionIndex($query = false)
    {
        if (! Module::isEnabled()) {
            throw new NotFoundHttpException('search is not enabled in this context');
        }

        $searchModel = new FrontendSearch();
        $searchModel->scenario = $searchModel::FRONTEND_SCENARIO;
        $result = null;
        $resultGroups = null;
        $searchInputWidget = null;

        if (Yii::$app->request->get('query')) {
            $searchModel->load(Yii::$app->request->get());
            if ($searchModel->validate()) {
                $resultGroups = $searchModel->getActiveResultGroups();
                $resultItems = $searchModel->search()->andWhere(['group' => array_keys($resultGroups)])->all();
                // build result array grouped by resultGroup ref_names to preserve group ordering from DB
                $result = [];
                foreach ($resultGroups as $ref => $group) {
                    $result[$ref] = [];
                }
                foreach ($resultItems as $item) {
                    // should not occur, but to be safe....
                    if (!array_key_exists($item['group'], $result)) {
                        continue;
                    }
                    $result[$item['group']][] = $item;
                }
            }
        }
        if (!empty($this->module->searchInputWidget)) {
            $searchInputWidget = $this->module->searchInputWidget;
        }

        return $this->render('index',
                             [
                                 'model'             => $searchModel,
                                 'resultGroups'      => $resultGroups,
                                 'result'            => $result,
                                 'searchInputWidget' => $searchInputWidget
                             ]
        );

    }

}
