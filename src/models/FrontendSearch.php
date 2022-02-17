<?php

namespace dmstr\activeRecordSearch\models;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "dmstr_search".
 */
class FrontendSearch extends Search
{

    /**
     * search string from form input
     * @var
     */
    public $query;

    /**
     * should query search made with and OR or
     * 1 = or
     * 0 = and
     * @var int
     */
    public $query_or = 0;

    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules['frontend_query'] = ['query', 'string', 'min' => 3  ];

        return $rules;
    }

    /**
     * @return string
     */
    public function formName()
    {
        return '';
    }

    /**
     * mak frontend search language aware
     *
     * @return query\SearchQuery
     */
    public static function find()
    {
        $query = parent::find();
        $query->andWhere([
                             'language' => \Yii::$app->language
                         ]);
        return $query;
    }

    /**
     * This is the search itself
     *
     * @return query\SearchQuery
     */
    public function search()
    {
        $query = self::find();
        $like_op = 'like';
        if ($this->query_or == 1) {
            $like_op = 'or like';
        }
        $query->andFilterWhere([$like_op, 'search_text', array_filter(explode(' ', $this->query))]);
        // removed due to ONLY_FULL_GROUP_BY issue
        // see: https://www.percona.com/blog/2019/05/13/solve-query-failures-regarding-only_full_group_by-sql-mode/
        // $query->groupBy(['group', 'model_id']);
        return $query;

    }

    /**
     * get (active) ResultGroups
     * due to translationBehaviour runtime magick,
     * we have to check status and sort by rank on the result-array. No (generic) way to do this in SQL.
     *
     * @return array
     */
    public function getActiveResultGroups()
    {
        $resGroups = [];
        foreach (SearchGroup::find()->indexBy('ref_name')->all() as $gKey => $gModel) {
            if ($gModel->status == 1) {
                $resGroups[$gKey] =  $gModel;
            }
        }

        ArrayHelper::multisort($resGroups, 'rank');
        return $resGroups;
    }

    /**
     * generate URL for one search result item, should be used in views
     *
     * @param $item
     *
     * @return string
     */
    public static function itemUrl($item)
    {
        $urlParts = array_merge([$item['route']], \yii\helpers\Json::decode($item['url_params']));
        return \yii\helpers\Url::to($urlParts);
    }
}
