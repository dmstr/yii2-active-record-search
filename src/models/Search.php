<?php

namespace dmstr\activeRecordSearch\models;

use dmstr\activeRecordSearch\models\base\Search as BaseSearch;

/**
 * This is the model class for table "dmstr_search".
 */
class Search extends BaseSearch
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
