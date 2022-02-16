<?php

namespace dmstr\activeRecordSearch\models\query;

/**
 * This is the ActiveQuery class for [[\dmstr\activeRecordSearch\models\SearchGroupTranslation]].
 *
 * @see \dmstr\activeRecordSearch\models\SearchGroupTranslation
 */
class SearchGroupTranslationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \dmstr\activeRecordSearch\models\SearchGroupTranslation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \dmstr\activeRecordSearch\models\SearchGroupTranslation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
