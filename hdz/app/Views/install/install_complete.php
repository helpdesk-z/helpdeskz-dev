<?php
/**
 * @var $this \CodeIgniter\View\View
 */
$this->extend('install/template');
$this->section('content');
?>
<h4 class="mb-3 f-w-400"><?php echo lang('Install.complete');?></h4>
<p><?php echo lang('Install.completeDescr');?></p>
<p><?php echo lang('Install.isLocked');?></p>
<div class="form-group">
    <a href="<?php echo site_url(route_to('staff_login'));?>" class="btn btn-lg btn-primary" target="_blank"><?php echo lang('Install.goToStaffPanel');?></a>
    <a href="<?php echo site_url(route_to('home'));?>" class="btn btn-lg btn-secondary" target="_blank"><?php echo lang('Install.goToHelpDesk');?></a>
</div>
<?php
$this->endSection();