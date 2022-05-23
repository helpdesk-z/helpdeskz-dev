<?php
/**
 * @var $this \CodeIgniter\View\View
 */
$this->extend('install/template');
$this->section('content');
?>
    <h4 class="mb-3 f-w-400"><?php echo lang('Install.upgradeWizard');?></h4>
<?php
if(isset($error_msg)){
    echo '<div class="alert alert-danger">'.$error_msg.'</div>';
}
echo '<p>'.lang_replace('Install.upgradeFromVersion',['%old%'=>$old_version,'%new%'=>HDZ_VERSION]).'</p>';
echo form_open('',[],['do'=>'submit','action'=>'install']);
?>
    <h5><?php echo lang('Install.administration');?></h5>
    <div class="form-group">
        <label><?php echo lang('Install.email');?></label>
        <input type="email" name="email" value="<?php echo set_value('email','');?>" class="form-control" required>
    </div>
    <div class="form-group">
        <label><?php echo lang('Install.password');?></label>
        <input type="password" name="password" class="form-control" required>
    </div>
<?php
echo '<button class="btn btn-primary">'.lang('Install.installHelpDesk').'</button>';
echo form_close();
$this->endSection();