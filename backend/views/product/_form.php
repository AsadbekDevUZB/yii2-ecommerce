<?php

use dosamigos\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Product $model */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="product-form">

    <?php $form = ActiveForm::begin([
            'options'=>[
                    'enctype'=>'multipart/form-data',
                    'method'=>'post'
            ]
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->widget(CKEditor::class, [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?>

    <?= $form->field($model, 'imageFile', [
        'template' => '
                <div class="custom-file">
                    {input}
                    {label}
                    {error}
                </div>
        ',
        'inputOptions'=>['class'=>'custom-file-input'],
        'labelOptions'=>['class'=>'custom-file-label']
        ])->textInput(['type'=>'file'])?>

    <?= $form->field($model, 'price')->textInput([
        'type' => 'number'
    ]) ?>

    <?= $form->field($model, 'status')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
