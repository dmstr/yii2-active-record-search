<?php

namespace dmstr\activeRecordSearch\widgets;

use dmstr\activeRecordSearch\models\FrontendSearch;
use dmstr\activeRecordSearch\Module;
use Yii;

/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2020 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class SearchInput extends \yii\base\Widget
{

    public $model = false;
    public $formAction = '/search/frontend/index';
    public $permission  = 'search_frontend';
    public $formId = false;
    public $wrapperClass = '';

    public function init()
    {
        if ($this->model === false) {
            $this->model = new FrontendSearch();
            $this->model->load(Yii::$app->request->get());
        }

        if ($this->formId === false) {
            $this->formId = 'frontend-search-form-' . $this->id;
        }
        parent::init();

    }

    public function run()
    {
        if (! Module::isEnabled()) {
            return '';
        }

        if ((!empty($this->permission)) && (! Yii::$app->user->can($this->permission))) {
            return '';
        }

        return $this->render('search-input', [
            'model' => $this->model,
            'formId' => $this->formId,
            'formAction' => $this->formAction,
            'wrapperClass' => $this->wrapperClass
        ]);
    }
}
