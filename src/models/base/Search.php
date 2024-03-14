<?php

namespace dmstr\activeRecordSearch\models\base;

use Yii;


/**
 * This is the base-model class for table "dmstr_search".
 *
 * @property integer $id
 * @property string $group
 * @property string $model_class
 * @property string $route
 * @property string $model_id
 * @property string $language
 * @property string $url_params
 * @property string $link_text
 * @property string $search_text
 *
 * @property \dmstr\activeRecordSearch\models\SearchGroup $groupRef
 * @property string $aliasModel
 */
abstract class Search extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dmstr_search}}';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
         $rules = parent::rules();
         $rules[] = [['group', 'model_id', 'search_text'], 'required'];
         $rules[] = [['search_text'], 'string'];
         $rules[] = [['group', 'model_class', 'route', 'model_id', 'url_params', 'link_text'], 'string', 'max' => 255];
         $rules[] = [['language'], 'string', 'max' => 7];
         $rules[] = [['group'], 'exist', 'skipOnError' => true, 'targetClass' => \dmstr\activeRecordSearch\models\SearchGroup::className(), 'targetAttribute' => ['group' => 'ref_name']];

         return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();

        $attributeLabels["id"] = Yii::t('search', 'ID');
        $attributeLabels["group"] = Yii::t('search', 'Group');
        $attributeLabels["model_class"] = Yii::t('search', 'Model Class');
        $attributeLabels["route"] = Yii::t('search', 'Route');
        $attributeLabels["model_id"] = Yii::t('search', 'Model ID');
        $attributeLabels["language"] = Yii::t('search', 'Language');
        $attributeLabels["url_params"] = Yii::t('search', 'Url Params');
        $attributeLabels["link_text"] = Yii::t('search', 'Link Text');
        $attributeLabels["search_text"] = Yii::t('search', 'Search Text');

        return $attributeLabels;
    }
    /**
    * return value from title|name + (first) primaryKey column as default
    * @return string
    */
    public function getLabel()
    {
        if (isset($this->title)) {
            return "{$this->title} (#{$this->id})";
        }
        if (isset($this->name)) {
            return "{$this->name} (#{$this->id})";
        }
        return $this->id;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupRef()
    {
        return $this->hasOne(\dmstr\activeRecordSearch\models\SearchGroup::className(), ['ref_name' => 'group']);
    }



    
    /**
     * @inheritdoc
     * @return \dmstr\activeRecordSearch\models\query\SearchQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \dmstr\activeRecordSearch\models\query\SearchQuery(get_called_class());
    }


}
