<?php

namespace dmstr\activeRecordSearch\models;

use dmstr\activeRecordSearch\models\base\SearchGroup as BaseSearchGroup;

/**
 * This is the model class for table "dmstr_search_group".
 */
class SearchGroup extends BaseSearchGroup
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        return $behaviors;
    }

    public function rules()
    {
        $rules = parent::rules();

        return $rules;
    }
}
