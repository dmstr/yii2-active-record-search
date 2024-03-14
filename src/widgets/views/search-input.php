<?php
/**
 *
 * @var \project\modules\dealer\models\FrontendSearch $model
 * @var string $formAction
 * @var string $formId
 * @var string $wrapperClass
 * @var bool $enableAutoSubmit
 * @var bool $showInputLabel
 * @var string $placeholderText
 * @var string $submitButtonText
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="search-input <?= $wrapperClass ?>">

    <?php $form = ActiveForm::begin(
        [
            'class' => 'search-input-form',
            'method' => 'get',
            'action' => \yii\helpers\Url::to($formAction),
            'id' => $formId,
        ]
    ) ?>

    <div class="search-input-form-group">

        <?= $form->field($model, 'query')->textInput(
            [
                'class' => 'form-control search-input-input',
                'placeholder' => $placeholderText,
                'onchange' => $enableAutoSubmit ? '$("#' . $formId . '").submit()' : null,
            ]
        )->label($showInputLabel ? $model->getAttributeLabel('query') : false) ?>

        <?= Html::submitButton(
            $submitButtonText,
            [
                'class' => 'btn btn-primary search-input-submit',
            ]
        ) ?>

    </div>

    <?php ActiveForm::end(); ?>

</div>
