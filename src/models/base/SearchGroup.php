<?php

namespace dmstr\activeRecordSearch\models\base;

use dosamigos\translateable\TranslateableBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;


/**
 * This is the base-model class for table "dmstr_search_group".
 *
 * @property integer $id
 * @property string $ref_name
 * @property string $created_at
 * @property string $updated_at
 *
 * @property \dmstr\activeRecordSearch\models\Search[] $searches
 * @property string $aliasModel
 */
abstract class SearchGroup extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dmstr_search_group}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
          $behaviors = parent::behaviors();

          $behaviors['timestamp'] = [
                'class' => TimestampBehavior::class,
                 'value' => new \yii\db\Expression('NOW()'),
          ];
                  $behaviors['translation'] = [
                'class' => TranslateableBehavior::class,
                // in case you renamed your relation, you can setup its name
                // 'relation' => 'translations',
                'skipSavingDuplicateTranslation' => true,
                'translationAttributes' => [
                    'status',
                    'name',
                    'rank'
                ],
                'deleteEvent' => \yii\db\ActiveRecord::EVENT_BEFORE_DELETE,
                'restrictDeletion' => TranslateableBehavior::DELETE_LAST
          ];

        return $behaviors;
    }

    public function transactions()
    {
        return [
            // enable transactions in delete case, to remove translations in the same transaction as the record
            self::SCENARIO_DEFAULT => self::OP_DELETE,
        ];
    }


    /**
    * @inheritdoc
    */
    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios ['crud'] = [
                    'ref_name',
                    'status',
                    'name',
                    'rank',

                  ];

        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
         $rules = parent::rules();
         $rules += $this->importTranslationAttributeRules();
         $rules[] = [['ref_name'], 'required'];
         $rules[] = [['ref_name'], 'string', 'max' => 255];

         return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();

        $attributeLabels["id"] = Yii::t('search', 'ID');
        $attributeLabels["ref_name"] = Yii::t('search', 'Ref Name');
        $attributeLabels["created_at"] = Yii::t('search', 'Created At');
        $attributeLabels["updated_at"] = Yii::t('search', 'Updated At');

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
    public function getSearches()
    {
        return $this->hasMany(\dmstr\activeRecordSearch\models\Search::className(), ['group' => 'ref_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(\dmstr\activeRecordSearch\models\SearchGroupTranslation::className(), ['group_id' => 'id']);
    }

    /**
    * get validation rules from translation* relationModels
    * @return array
    */
    protected function importTranslationAttributeRules() {

        $rules = [];

        foreach ($this->getBehaviors() as $key => $behavoir) {

            if ($behavoir instanceof TranslateableBehavior) {

                $translationModelClass = $this->getRelation($behavoir->relation)->modelClass;
                $importRules = (new $translationModelClass)->rules();
                foreach ($importRules as $rule) {
                    foreach ($rule[0] as $rule_key => $attribute) {
                        if (!in_array($attribute, $behavoir->translationAttributes)) {
                            unset ($rule[0][$rule_key]);
                        }
                    }
                    if (!empty($rule[0])) {
                        $rules[] = $rule;
                    }
                }
            } else {
                continue;
            }
        }

        return $rules;
    }

    
    /**
     * @inheritdoc
     * @return \dmstr\activeRecordSearch\models\query\SearchGroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \dmstr\activeRecordSearch\models\query\SearchGroupQuery(get_called_class());
    }


}
