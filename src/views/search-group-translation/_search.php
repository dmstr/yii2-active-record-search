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
 * @var dmstr\activeRecordSearch\models\search\SearchGroupTranslation $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="search-group-translation-search">

    <?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

    		<?php echo $form->field($model, 'id') ?>

		<?php echo $form->field($model, 'group_id') ?>

		<?php echo $form->field($model, 'language') ?>

		<?php echo $form->field($model, 'status') ?>

		<?php echo $form->field($model, 'name') ?>

		<?php // echo $form->field($model, 'rank') ?>

		<?php // echo $form->field($model, 'created_at') ?>

		<?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('search', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?php echo Html::resetButton(Yii::t('search', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
