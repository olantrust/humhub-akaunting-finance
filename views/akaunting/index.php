<?php
/* @var $this yii\web\View */

?>
<?php //echo is_file(Yii::getAlias('@themes') . '/' . $this->theme->name . '/views/layouts/_iframe_resize.php') ?>

<iframe id="akaunting_iframe" src="<?= $akaunting_url ?>" frameborder="0" style="height:100%;width:100%;border-radius:4px"></iframe>

<?php // to resize iframe base on document height
if(is_file(Yii::getAlias('@themes') . '/' . $this->theme->name . '/views/layouts/_iframe_resize.php'))
{
    echo $this->render('@themes' . '/' . $this->theme->name . '/views/layouts/_iframe_resize', ['ID_iframe' => 'akaunting_iframe']);
}
?>

<?php /* if ($this->theme->name == 'Olan'): ?>
<?php $this->beginBlock('sidebar'); ?>
<?= Sidebar::widget(['space' => $space, 'widgets' => [
    [ActivityStreamViewer::class, ['contentContainer' => $space], ['sortOrder' => 10]],
    [PendingApprovals::class, ['space' => $space], ['sortOrder' => 20]],
    [Members::class, ['space' => $space], ['sortOrder' => 30]]
]]);
?>
<?php $this->endBlock(); ?>
<?php endif; */?>