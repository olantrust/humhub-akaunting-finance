<?php

use humhub\widgets\Button;

// Register our module assets, this could also be done within the controller
\olan\akauntingfinance\assets\Assets::register($this);

$displayName = (Yii::$app->user->isGuest) ? Yii::t('AkauntingFinanceModule.base', 'Guest') : Yii::$app->user->getIdentity()->displayName;

// Add some configuration to our js module
$this->registerJsConfig("finance", [
    'username' => (Yii::$app->user->isGuest) ? $displayName : Yii::$app->user->getIdentity()->username,
    'text' => [
        'hello' => Yii::t('AkauntingFinanceModule.base', 'Hi there {name}!', ["name" => $displayName])
    ]
])

?>

<div class="panel-heading"><strong>Finance</strong> <?= Yii::t('AkauntingFinanceModule.base', 'overview') ?></div>

<div class="panel-body">
    <p><?= Yii::t('AkauntingFinanceModule.base', 'Hello World!') ?></p>

    <?=  Button::primary(Yii::t('AkauntingFinanceModule.base', 'Say Hello!'))->action("akaunting-finance.hello")->loader(false); ?></div>
