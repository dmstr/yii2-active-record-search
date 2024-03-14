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
    public $enableAutoSubmit = true;
    public $showInputLabel = true;
    public $placeholderText;
    public $submitButtonText;

    public function init()
    {
        if ($this->model === false) {
            $this->model = new FrontendSearch();
            $this->model->load(Yii::$app->request->get());
        }

        if ($this->formId === false) {
            $this->formId = 'frontend-search-form-' . $this->id;
        }

        if (empty($this->placeholderText)) {
            $this->placeholderText = Yii::t('search', 'Search');
        }

        if (empty($this->submitButtonText)) {
            $this->submitButtonText = Yii::t('search', 'Search');
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
            'wrapperClass' => $this->wrapperClass,
            'enableAutoSubmit' => $this->enableAutoSubmit,
            'showLabel' => $this->showInputLabel,
            'placeholderText' => $this->placeholderText,
            'submitButtonText' => $this->submitButtonText,
        ]);
    }
}
