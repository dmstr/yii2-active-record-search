<?php
/**
 * /app/runtime/giiant/eeda5c365686c9888dbc13dbc58f89a1
 *
 * @package default
 */


use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 *
 * @var yii\web\View $this
 * @var dmstr\activeRecordSearch\models\search\Search $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="search-search">

    <?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

    		<?php echo $form->field($model, 'id') ?>

		<?php echo $form->field($model, 'group') ?>

		<?php echo $form->field($model, 'model_class') ?>

		<?php echo $form->field($model, 'route') ?>

		<?php echo $form->field($model, 'model_id') ?>

		<?php // echo $form->field($model, 'language') ?>

		<?php // echo $form->field($model, 'url_params') ?>

		<?php // echo $form->field($model, 'link_text') ?>

		<?php // echo $form->field($model, 'search_text') ?>

    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('search', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?php echo Html::resetButton(Yii::t('search', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
