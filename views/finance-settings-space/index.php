<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use humhub\modules\space\modules\manage\widgets\DefaultMenu;
use humhub\widgets\Button;

\olan\akauntingfinance\assets\Assets::register($this);

?>
<div class="panel panel-default">
    <div>
        <div class="panel-heading">
            <?= Yii::t('AkauntingFinanceModule.manage', '<strong>Akaunting</strong> link settings'); ?>
        </div>
    </div>

    <?= DefaultMenu::widget(['space' => $space]); ?>

    <div class="panel-body">
        <?php if(!empty($link_space)): ?>
            <h1 class="text-center">Congratulations you are linked with Akaunting!
            <strong><?= Html::a('Click Here <i class="fa fa-external-link"></i>', $API_url . '/common/companies/' . $link_space->akc_id . '/switch', ['target' => '_blank']) ?></strong></h1>
        <?php else: ?>
            <!-- <h1>//TODO : link space</h1> -->
            <center>
                <?= Button::asLink('Link Space')->icon('fa-link')
                    ->action('akaunting-finance.linkSpace', $space->createUrl('/akaunting-finance/finance-settings-space/link-space'))
                    ->loader(true)
                    ->cssClass('btn btn-primary')->options(['data-ui-loader'])->visible(true) ?>
            </center>
        <?php endif; ?>
    </div>
</div>