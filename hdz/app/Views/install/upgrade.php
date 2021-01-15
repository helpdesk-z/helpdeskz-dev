<?php
/**
 * @var $this \CodeIgniter\View\View
 */
$this->extend('install/template');
$this->section('content');
?>
<h4 class="mb-3 f-w-400"><?php echo lang('Install.upgradeWizard');?></h4>
<p><?php echo lang_replace('Install.upgradeInfo',[
        '%version%' => '<span class="font-weight-bold">'.HDZ_VERSION.'</span>'
    ]);
?></p>
<?php
echo form_open('',[],['do'=>'submit']);
echo '<button class="btn btn-primary">'.lang('Install.continue').'</button>';
echo form_close();
$this->endSection();