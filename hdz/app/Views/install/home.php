<?php
/**
 * @var $this \CodeIgniter\View\View
 */
$this->extend('install/template');
$this->section('content');
?>
<h4 class="mb-3 f-w-400"><?php echo lang('Install.welcome');?></h4>
<p><?php echo lang_replace('Install.welcomeInfo',[
        '%version%' => HDZ_VERSION,
    ]);
?></p>
<a href="<?php echo site_url(route_to('install_wizard'));?>" class="btn btn-primary"><?php echo lang('Install.installSite');?></a>

    <a href="<?php echo site_url(route_to('upgrade_wizard'));?>" class="btn btn-primary"><?php echo lang('Install.upgradeSite');?></a>
<?php
$this->endSection();