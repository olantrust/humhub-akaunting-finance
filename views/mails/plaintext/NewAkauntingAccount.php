<?php

use yii\helpers\Html;
?>
<?php echo strip_tags(Yii::t('FinanceModule.base', '<strong>Akaunting</strong></strong> your credentials')); ?>


<?php echo strip_tags(Yii::t('FinanceModule.base', 'Hello')); ?> <?php echo Html::encode($user->displayName); ?>,

<?php echo strip_tags(str_replace("<br>", "\n", Yii::t('FinanceModule.base', 'Below is your Akaunting Credentials.'))); ?>


<?php echo strip_tags(Yii::t('FinanceModule.base', 'Login Url')); ?>: <?php echo urldecode($login_url); ?>


<?php echo strip_tags(Yii::t('FinanceModule.base', 'Username')); ?>: <?php echo $user->email; ?>

<?php echo strip_tags(Yii::t('FinanceModule.base', 'Password')); ?>: <?php echo $password; ?>
