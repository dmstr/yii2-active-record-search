<?php
/**
 * /app/runtime/giiant/fccccf4deb34aed738291a9c38e87215
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
$this->params['breadcrumbs'][] = ['label' => Yii::t('search', 'Search Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud search-group-create">

    <h1>
        <?php echo Yii::t('search', 'Search Group') ?>
        <small>
                        <?php echo Html::encode($model->label) ?>
        </small>
    </h1>

    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?php echo             Html::a(
	Yii::t('search', 'Cancel'),
	\yii\helpers\Url::previous(),
	['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <hr />

    <?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
