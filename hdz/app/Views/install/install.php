<?php
/**
 * @var $this \CodeIgniter\View\View
 */
$this->extend('install/template');
$this->section('content');
?>
<h4 class="mb-3 f-w-400"><?php echo lang('Install.installWizard');?></h4>
<p><?php echo lang('Install.installInfo1');?></p>
<p><?php echo lang_replace('Install.installInfo2',[
        '[a]' => '<a href="http://community.helpdeskz.com/" target="_blank">',
        '[/a]' => '</a>',
    ]);
?></p>
<?php
echo form_open('',[],['do'=>'submit']);
echo '<button class="btn btn-primary">'.lang('Install.continue').'</button>';
echo form_close();
$this->endSection();