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
 * @var dmstr\activeRecordSearch\models\SearchGroupTranslation $model
 */
$this->title = Yii::t('search', 'Search Group Translation');
$this->params['breadcrumbs'][] = ['label' => Yii::t('search', 'Search Group Translations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud search-group-translation-create">

    <h1>
        <?php echo Yii::t('search', 'Search Group Translation') ?>
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
