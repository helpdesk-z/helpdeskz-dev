<?php
/**
 * @var $this \CodeIgniter\View\View
 */
$this->extend('install/template');
$this->section('content');
?>
<h4 class="mb-3 f-w-400"><?php echo lang('Install.installWizard');?></h4>
<p><?php echo lang('Install.someErrors');?></p>
<ul>
    <?php
    foreach ($error_msg as $msg){
        echo '<li>'.$msg.'</li>';
    }
    ?>
</ul>
<?php
echo form_open('',[],['do'=>'submit']);
echo '<button class="btn btn-primary">'.lang('Install.tryAgain').'</button>';
echo form_close();
$this->endSection();