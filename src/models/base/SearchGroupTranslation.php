<?php

namespace dmstr\activeRecordSearch\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;


/**
 * This is the base-model class for table "dmstr_search_group_translation".
 *
 * @property integer $id
 * @property integer $group_id
 * @property string $language
 * @property integer $status
 * @property string $name
 * @property string $rank
 * @property string $created_at
 * @property string $updated_at
 *
 * @property \dmstr\activeRecordSearch\models\SearchGroup $group
 * @property string $aliasModel
 */
abstract class SearchGroupTranslation extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dmstr_search_group_translation}}';
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
    public function rules()
    {
         $rules = parent::rules();
         $rules[] = [['group_id', 'name'], 'required'];
         $rules[] = [['group_id', 'status'], 'integer'];
         $rules[] = [['language'], 'string', 'max' => 7];
         $rules[] = [['name'], 'string', 'max' => 255];
         $rules[] = [['rank'], 'string', 'max' => 45];
         $rules[] = [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => \dmstr\activeRecordSearch\models\SearchGroup::className(), 'targetAttribute' => ['group_id' => 'id']];

         return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();

        $attributeLabels["id"] = Yii::t('search', 'ID');
        $attributeLabels["group_id"] = Yii::t('search', 'Group ID');
        $attributeLabels["language"] = Yii::t('search', 'Language');
        $attributeLabels["status"] = Yii::t('search', 'Status');
        $attributeLabels["name"] = Yii::t('search', 'Name');
        $attributeLabels["rank"] = Yii::t('search', 'Rank');
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
    public function getGroup()
    {
        return $this->hasOne(\dmstr\activeRecordSearch\models\SearchGroup::className(), ['id' => 'group_id']);
    }



    
    /**
     * @inheritdoc
     * @return \dmstr\activeRecordSearch\models\query\SearchGroupTranslationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \dmstr\activeRecordSearch\models\query\SearchGroupTranslationQuery(get_called_class());
    }


}
