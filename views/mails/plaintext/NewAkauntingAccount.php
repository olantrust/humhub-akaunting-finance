<?php

use yii\helpers\Html;
?>
<?php echo strip_tags(Yii::t('AkauntingFinanceModule.base', '<strong>Akaunting</strong></strong> your credentials')); ?>


<?php echo strip_tags(Yii::t('AkauntingFinanceModule.base', 'Hello')); ?> <?php echo Html::encode($user->displayName); ?>,

<?php echo strip_tags(str_replace("<br>", "\n", Yii::t('AkauntingFinanceModule.base', 'Below is your Akaunting Credentials.'))); ?>


<?php echo strip_tags(Yii::t('AkauntingFinanceModule.base', 'Login Url')); ?>: <?php echo urldecode($login_url); ?>


<?php echo strip_tags(Yii::t('AkauntingFinanceModule.base', 'Username')); ?>: <?php echo $user->email; ?>

<?php echo strip_tags(Yii::t('AkauntingFinanceModule.base', 'Password')); ?>: <?php echo $password; ?>
