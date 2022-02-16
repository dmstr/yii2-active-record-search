<?php
/**
 * /app/runtime/giiant/d4b4964a63cc95065fa0ae19074007ee
 *
 * @package default
 */


use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/**
 *
 * @var yii\web\View $this
 * @var dmstr\activeRecordSearch\models\SearchGroup $model
 */
$copyParams = $model->attributes;

$this->title = Yii::t('search', 'Search Group');
$this->params['breadcrumbs'][] = ['label' => Yii::t('search', 'Search Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('search', 'View');
?>
<div class="giiant-crud search-group-view">

    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <?php echo \Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>

    <h1>
        <?php echo Yii::t('search', 'Search Group') ?>
        <small>
            <?php echo Html::encode($model->label) ?>
        </small>
    </h1>


    <div class="clearfix crud-navigation">

        <!-- menu buttons -->
        <div class='pull-left'>
            <?php echo Html::a(
	'<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('search', 'Edit'),
	[ 'update', 'id' => $model->id],
	['class' => 'btn btn-info']) ?>

            <?php echo Html::a(
	'<span class="glyphicon glyphicon-copy"></span> ' . Yii::t('search', 'Copy'),
	['create', 'id' => $model->id, 'SearchGroup'=>$copyParams],
	['class' => 'btn btn-success']) ?>

            <?php echo Html::a(
	'<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('search', 'New'),
	['create'],
	['class' => 'btn btn-success']) ?>
        </div>

        <div class="pull-right">
            <?php echo Html::a('<span class="glyphicon glyphicon-list"></span> '
	. Yii::t('search', 'Full list'), ['index'], ['class'=>'btn btn-default']) ?>
        </div>

    </div>

    <hr/>

    <?php $this->beginBlock('dmstr\activeRecordSearch\models\SearchGroup'); ?>


    <?php echo DetailView::widget([
		'model' => $model,
		'attributes' => [
			'ref_name',
		],
	]); ?>


    <hr/>

    <?php echo Html::a('<span class="glyphicon glyphicon-trash"></span> ' . Yii::t('search', 'Delete'), ['delete', 'id' => $model->id],
	[
		'class' => 'btn btn-danger',
		'data-confirm' => '' . Yii::t('search', 'Are you sure to delete this item?') . '',
		'data-method' => 'post',
	]); ?>
    <?php $this->endBlock(); ?>



<?php $this->beginBlock('Searches'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php echo Html::a(
	'<span class="glyphicon glyphicon-list"></span> ' . Yii::t('search', 'List All') . ' Searches',
	['/search/search/index'],
	['class'=>'btn text-muted btn-xs']
) ?>
  <?php echo Html::a(
	'<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('search', 'New') . ' Search',
	['/search/search/create', 'Search' => ['group' => $model->id]],
	['class'=>'btn btn-success btn-xs']
); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Searches', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Searches ul.pagination a, th a']) ?>
<?php echo
'<div class="table-responsive">'
	. \yii\grid\GridView::widget([
		'layout' => '{summary}{pager}<br/>{items}{pager}',
		'dataProvider' => new \yii\data\ActiveDataProvider([
				'query' => $model->getSearches(),
				'pagination' => [
					'pageSize' => 20,
					'pageParam'=>'page-searches',
				]
			]),
		'pager'        => [
			'class'          => yii\widgets\LinkPager::className(),
			'firstPageLabel' => Yii::t('search', 'First'),
			'lastPageLabel'  => Yii::t('search', 'Last')
		],
		'columns' => [
			[
				'class'      => 'yii\grid\ActionColumn',
				'template'   => '{view} {update}',
				'contentOptions' => ['nowrap'=>'nowrap'],
				'urlCreator' => function ($action, $model, $key, $index) {
					// using the column name as key, not mapping to 'id' like the standard generator
					$params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
					$params[0] = '/search/search' . '/' . $action;
					$params['Search'] = ['group' => $model->primaryKey()[0]];
					return $params;
				},
				'buttons'    => [

				],
				'controller' => '/search/search'
			],
			'id',
			'model_class',
			'route',
			'model_id',
			'language',
			'url_params:url',
			'link_text',
			'search_text:ntext',
		]
	])
	. '</div>'
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Translations'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php echo Html::a(
	'<span class="glyphicon glyphicon-list"></span> ' . Yii::t('search', 'List All') . ' Translations',
	['/search/search-group-translation/index'],
	['class'=>'btn text-muted btn-xs']
) ?>
  <?php echo Html::a(
	'<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('search', 'New') . ' Translation',
	['/search/search-group-translation/create', 'SearchGroupTranslation' => ['group_id' => $model->id]],
	['class'=>'btn btn-success btn-xs']
); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Translations', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Translations ul.pagination a, th a']) ?>
<?php echo
'<div class="table-responsive">'
	. \yii\grid\GridView::widget([
		'layout' => '{summary}{pager}<br/>{items}{pager}',
		'dataProvider' => new \yii\data\ActiveDataProvider([
				'query' => $model->getTranslations(),
				'pagination' => [
					'pageSize' => 20,
					'pageParam'=>'page-translations',
				]
			]),
		'pager'        => [
			'class'          => yii\widgets\LinkPager::className(),
			'firstPageLabel' => Yii::t('search', 'First'),
			'lastPageLabel'  => Yii::t('search', 'Last')
		],
		'columns' => [
			[
				'class'      => 'yii\grid\ActionColumn',
				'template'   => '{view} {update}',
				'contentOptions' => ['nowrap'=>'nowrap'],
				'urlCreator' => function ($action, $model, $key, $index) {
					// using the column name as key, not mapping to 'id' like the standard generator
					$params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
					$params[0] = '/search/search-group-translation' . '/' . $action;
					$params['SearchGroupTranslation'] = ['group_id' => $model->primaryKey()[0]];
					return $params;
				},
				'buttons'    => [

				],
				'controller' => '/search/search-group-translation'
			],
			'id',
			'language',
			'status',
			'name',
			'rank',
			'created_at',
			'updated_at',
		]
	])
	. '</div>'
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


    <?php echo Tabs::widget(
	[
		'id' => 'relation-tabs',
		'encodeLabels' => false,
		'items' => [
			[
				'label'   => '<b class=""># '.Html::encode($model->id).'</b>',
				'content' => $this->blocks['dmstr\activeRecordSearch\models\SearchGroup'],
				'active'  => true,
			],
			[
				'content' => $this->blocks['Searches'],
				'label'   => '<small>Searches <span class="badge badge-default">'. $model->getSearches()->count() . '</span></small>',
				'active'  => false,
			],
			[
				'content' => $this->blocks['Translations'],
				'label'   => '<small>Translations <span class="badge badge-default">'. $model->getTranslations()->count() . '</span></small>',
				'active'  => false,
			],
		]
	]
);
?>
</div>
