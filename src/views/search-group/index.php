<?php
/**
 * /app/runtime/giiant/a0a12d1bd32eaeeb8b2cff56d511aa22
 *
 * @package default
 */


use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var dmstr\activeRecordSearch\models\search\SearchGroup $searchModel
 */
$this->title = Yii::t('search', 'Search Groups');
$this->params['breadcrumbs'][] = $this->title;

if (isset($actionColumnTemplates)) {
	$actionColumnTemplate = implode(' ', $actionColumnTemplates);
	$actionColumnTemplateString = $actionColumnTemplate;
} else {
	Yii::$app->view->params['pageButtons'] = Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('search', 'New'), ['create'], ['class' => 'btn btn-success']);
	$actionColumnTemplateString = "{view} {update} {delete}";
}
$actionColumnTemplateString = '<div class="action-buttons">'.$actionColumnTemplateString.'</div>';
?>
<div class="giiant-crud search-group-index">

    <?php
//             echo $this->render('_search', ['model' =>$searchModel]);
?>


    <?php \yii\widgets\Pjax::begin(['id'=>'pjax-main', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success'=>'function(){alert("yo")}']]) ?>

    <h1>
        <?php echo Yii::t('search', 'Search Groups') ?>
        <small>
            List
        </small>
    </h1>
    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?php echo Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('search', 'New'), ['create'], ['class' => 'btn btn-success']) ?>
        </div>

        <div class="pull-right">


            <?php echo
\yii\bootstrap\ButtonDropdown::widget(
	[
		'id' => 'giiant-relations',
		'encodeLabel' => false,
		'label' => '<span class="glyphicon glyphicon-paperclip"></span> ' . Yii::t('search', 'Relations'),
		'dropdown' => [
			'options' => [
				'class' => 'dropdown-menu-right'
			],
			'encodeLabels' => false,
			'items' => [
				[
					'url' => ['/search/search/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('search', 'Search'),
				],
				[
					'url' => ['/search/search-group-translation/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('search', 'Search Group Translation'),
				],

			]
		],
		'options' => [
			'class' => 'btn-default'
		]
	]
);
?>
        </div>
    </div>

    <hr />

    <div class="table-responsive">
        <?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'pager' => [
			'class' => yii\widgets\LinkPager::className(),
			'firstPageLabel' => Yii::t('search', 'First'),
			'lastPageLabel' => Yii::t('search', 'Last'),
		],
		'filterModel' => $searchModel,
		'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
		'headerRowOptions' => ['class'=>'x'],
		'columns' => [
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => $actionColumnTemplateString,
				'buttons' => [
					'view' => function ($url, $model, $key) {
						$options = [
							'title' => Yii::t('search', 'View'),
							'aria-label' => Yii::t('search', 'View'),
							'data-pjax' => '0',
						];
						return Html::a('<span class="glyphicon glyphicon-file"></span>', $url, $options);
					}


				],
				'urlCreator' => function($action, $model, $key, $index) {
					// using the column name as key, not mapping to 'id' like the standard generator
					$params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
					$params[0] = \Yii::$app->controller->id ? \Yii::$app->controller->id . '/' . $action : $action;
					return Url::toRoute($params);
				},
				'contentOptions' => ['nowrap'=>'nowrap']
			],
			'ref_name',
		],
	]); ?>
    </div>

</div>


<?php \yii\widgets\Pjax::end() ?>
