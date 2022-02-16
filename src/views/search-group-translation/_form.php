<?php
/**
 * /app/runtime/giiant/4b7e79a8340461fe629a6ac612644d03
 *
 * @package default
 */


use yii\bootstrap\ActiveForm;
use yii\bootstrap\Tabs;
use yii\helpers\Html;

/**
 *
 * @var yii\web\View $this
 * @var dmstr\activeRecordSearch\models\SearchGroupTranslation $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="search-group-translation-form">

    <?php $form = ActiveForm::begin([
		'id' => 'SearchGroupTranslation',
		'layout' => 'horizontal',
		'enableClientValidation' => true,
		'errorSummaryCssClass' => 'error-summary alert alert-danger',
		'fieldConfig' => [
			'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
			'horizontalCssClasses' => [
				'label' => 'col-sm-2',
				//'offset' => 'col-sm-offset-4',
				'wrapper' => 'col-sm-8',
				'error' => '',
				'hint' => '',
			],
		],
	]
);
?>

    <div class="">
        <?php $this->beginBlock('main'); ?>

        <p>


<!-- attribute group_id -->
            <?php echo $form->field($model, 'group')->dropDownList(\yii\helpers\ArrayHelper::map(dmstr\activeRecordSearch\models\SearchGroup::find()->all(), 'ref_name', 'label')); ?>

<!-- attribute name -->
			<?php echo $form->field($model, 'name'); ?>

<!-- attribute status -->
            <?php echo $form->field($model, 'status')->dropDownList([0 => YII::t('crud', 'inactive'), 1 => YII::t('crud', 'active')]); ?>

<!-- attribute language -->
			<?php echo $form->field($model, 'language'); ?>

<!-- attribute rank -->
			<?php echo $form->field($model, 'rank'); ?>
        </p>
        <?php $this->endBlock(); ?>

        <?php echo
Tabs::widget(
	[
		'encodeLabels' => false,
		'items' => [
			[
				'label'   => Yii::t('search', 'SearchGroupTranslation'),
				'content' => $this->blocks['main'],
				'active'  => true,
			],
		]
	]
);
?>
        <hr/>

        <?php echo $form->errorSummary($model); ?>

        <?php echo Html::submitButton(
	'<span class="glyphicon glyphicon-check"></span> ' .
	($model->isNewRecord ? Yii::t('search', 'Create') : Yii::t('search', 'Save')),
	[
		'id' => 'save-' . $model->formName(),
		'class' => 'btn btn-success'
	]
);
?>

        <?php ActiveForm::end(); ?>

    </div>

</div>
