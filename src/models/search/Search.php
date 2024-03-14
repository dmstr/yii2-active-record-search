<?php
/**
 * /app/src/../runtime/giiant/e0080b9d6ffa35acb85312bf99a557f2
 *
 * @package default
 */


namespace dmstr\activeRecordSearch\models\search;

use dmstr\activeRecordSearch\models\Search as SearchModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Search represents the model behind the search form about `dmstr\activeRecordSearch\models\Search`.
 */
class Search extends SearchModel
{

	/**
	 *
	 * @inheritdoc
	 * @return unknown
	 */
	public function rules() {
		return [
			[['id'], 'integer'],
			[['model_class', 'route', 'model_id', 'language', 'search_text', 'group'], 'safe'],
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
		$query = SearchModel::find();

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
				'model_id' => $this->model_id,
				'group' => $this->group,
			]);

		$query->andFilterWhere(['like', 'model_class', $this->model_class])
		->andFilterWhere(['like', 'route', $this->route])
		->andFilterWhere(['like', 'language', $this->language])
		->andFilterWhere(['like', 'search_text', $this->search_text]);

		return $dataProvider;
	}


}
