<?php
/**
 * /app/runtime/giiant/e0080b9d6ffa35acb85312bf99a557f2
 *
 * @package default
 */


namespace dmstr\activeRecordSearch\models\search;

use dmstr\activeRecordSearch\models\SearchGroupTranslation as SearchGroupTranslationModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SearchGroupTranslation represents the model behind the search form about `dmstr\activeRecordSearch\models\SearchGroupTranslation`.
 */
class SearchGroupTranslation extends SearchGroupTranslationModel
{

	/**
	 *
	 * @inheritdoc
	 * @return unknown
	 */
	public function rules() {
		return [
			[['id', 'group_id', 'status'], 'integer'],
			[['language', 'name', 'rank', 'created_at', 'updated_at'], 'safe'],
		];
	}


	/**
	 *
	 * @inheritdoc
	 * @return unknown
	 */
	public function scenarios() {
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}


	/**
	 * Creates data provider instance with search query applied
	 *
	 *
	 * @param array   $params
	 * @return ActiveDataProvider
	 */
	public function search($params) {
		$query = SearchGroupTranslationModel::find();

		$dataProvider = new ActiveDataProvider([
				'query' => $query,
			]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		$query->andFilterWhere([
				'id' => $this->id,
				'group_id' => $this->group_id,
				'status' => $this->status,
				'created_at' => $this->created_at,
				'updated_at' => $this->updated_at,
			]);

		$query->andFilterWhere(['like', 'language', $this->language])
		->andFilterWhere(['like', 'name', $this->name])
		->andFilterWhere(['like', 'rank', $this->rank]);

		return $dataProvider;
	}


}
