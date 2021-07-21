<?php

use humhub\widgets\Button;

// Register our module assets, this could also be done within the controller
\olan\finance\assets\Assets::register($this);

$displayName = (Yii::$app->user->isGuest) ? Yii::t('FinanceModule.base', 'Guest') : Yii::$app->user->getIdentity()->displayName;

// Add some configuration to our js module
$this->registerJsConfig("finance", [
    'username' => (Yii::$app->user->isGuest) ? $displayName : Yii::$app->user->getIdentity()->username,
    'text' => [
        'hello' => Yii::t('FinanceModule.base', 'Hi there {name}!', ["name" => $displayName])
    ]
])

?>

<div class="panel-heading"><strong>Finance</strong> <?= Yii::t('FinanceModule.base', 'overview') ?></div>

<div class="panel-body">
    <p><?= Yii::t('FinanceModule.base', 'Hello World!') ?></p>

    <?=  Button::primary(Yii::t('FinanceModule.base', 'Say Hello!'))->action("finance.hello")->loader(false); ?></div>
