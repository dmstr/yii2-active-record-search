<?php

namespace dmstr\activeRecordSearch\models;

use dmstr\activeRecordSearch\models\base\SearchGroupTranslation as BaseSearchGroupTranslation;

/**
 * This is the model class for table "dmstr_search_group_translation".
 */
class SearchGroupTranslation extends BaseSearchGroupTranslation
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
