<?php

use humhub\widgets\Button;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

\olan\finance\assets\Assets::register($this);

?>
<div class="panel panel-default">
    <div class="panel-heading"><strong><?= Yii::t('FinanceModule.base', 'Akaunting') ?></strong> <?= Yii::t('FinanceModule.base', 'configuration') ?></div>

    <div class="panel-body">
        <?php $form = ActiveForm::begin(); ?>

            <?php
            $link = '';

            if(!empty($model->API_url))
            {
                $link = "<div class='input-group-addon'>". Html::a('View <i class="fa fa-external-link"></i>', $model->API_url, ['target' => '_blank']) . "</div>";
            }
            ?>

            <?= $form->field($model, 'API_url', [
                    'template' => "{label}\n<div class='input-group'>{input}
                        <div class='input-group-addon'>/api/</div>" . $link . "</div>\n{hint}\n{error}"
                ])->textInput(['maxlength' => true]); ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'API_user')->textInput(['maxlength' => true]); ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'API_pass')->passwordInput(['maxlength' => true]); ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('FinanceModule.base', 'Save'), ['class' => 'btn btn-success']) ?>

                <?= Button::asLink(Yii::t('FinanceModule.base', 'Test & Sync'))->icon('fa-cog')
                    ->action('finance.testApi', Url::to(['/finance/admin/test']))
                    ->loader(true)
                    ->cssClass('btn btn-primary pull-right')->options(['data-ui-loader'])->visible(true) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>