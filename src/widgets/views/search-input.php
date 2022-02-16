<?php
/**
 *
 * @var \project\modules\dealer\models\FrontendSearch $model
 * @var string $formAction
 * @var string $formId
 * @var string $wrapperClass
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
                'placeholder' => Yii::t('search', 'Search'),
                'onchange' => '$("#' . $formId . '").submit()',
            ]
        ) ?>

        <?= Html::submitButton(
            Yii::t('search', 'Search'),
            [
                'class' => 'btn btn-primary search-input-submit',
            ]
        ) ?>

    </div>

    <?php ActiveForm::end(); ?>

</div>
