<?php
/**
 * /app/runtime/giiant/fcd70a9bfdf8de75128d795dfc948a74
 *
 * @package default
 */


use yii\helpers\Html;

/**
 *
 * @var yii\web\View $this
 * @var dmstr\activeRecordSearch\models\SearchGroup $model
 */
$this->title = Yii::t('search', 'Search Group');
$this->params['breadcrumbs'][] = ['label' => Yii::t('search', 'Search Group'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('search', 'Edit');
?>
<div class="giiant-crud search-group-update">

    <h1>
        <?php echo Yii::t('search', 'Search Group') ?>
        <small>
                        <?php echo Html::encode($model->label) ?>
        </small>
    </h1>

    <div class="crud-navigation">
        <?php echo Html::a('<span class="glyphicon glyphicon-file"></span> ' . Yii::t('search', 'View'), ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </div>

    <hr />

    <?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
