<?php

namespace dmstr\activeRecordSearch\models\query;

/**
 * This is the ActiveQuery class for [[\dmstr\activeRecordSearch\models\SearchGroup]].
 *
 * @see \dmstr\activeRecordSearch\models\SearchGroup
 */
class SearchGroupQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \dmstr\activeRecordSearch\models\SearchGroup[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \dmstr\activeRecordSearch\models\SearchGroup|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
