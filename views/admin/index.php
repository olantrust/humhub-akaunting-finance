<?php

use humhub\widgets\Button;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

\olan\akauntingfinance\assets\Assets::register($this);

?>
<div class="panel panel-default">
    <div class="panel-heading"><strong><?= Yii::t('AkauntingFinanceModule.base', 'Akaunting') ?></strong> <?= Yii::t('AkauntingFinanceModule.base', 'configuration') ?></div>

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
                <?= Html::submitButton(Yii::t('AkauntingFinanceModule.base', 'Save'), ['class' => 'btn btn-success']) ?>

                <?= Button::asLink(Yii::t('AkauntingFinanceModule.base', 'Test & Sync'))->icon('fa-cog')
                    ->action('akaunting-finance.testApi', Url::to(['/akaunting-finance/admin/test']))
                    ->loader(true)
                    ->cssClass('btn btn-primary pull-right')->options(['data-ui-loader'])->visible(true) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>