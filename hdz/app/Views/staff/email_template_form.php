<?php
/**
 * @var $this \CodeIgniter\View\View
 */
$this->extend('staff/template');
$this->section('content');
?>
    <!-- Page Header -->
    <div class="page-header row no-gutters py-4">
        <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
            <span class="text-uppercase page-subtitle"><?php echo lang('Admin.settings.emailTemplates');?></span>
            <h3 class="page-title"><?php echo lang('Admin.settings.editEmailTemplate');?></h3>
        </div>
    </div>
    <!-- End Page Header -->


<?php
if(isset($error_msg)){
    echo '<div class="alert alert-danger">'.$error_msg.'</div>';
}
if(isset($success_msg)){
    echo '<div class="alert alert-success">'.$success_msg.'</div>';
}
?>
    <div class="card">
        <div class="card-header border-bottom">
            <h6 class="mb-0"><?php echo $template->name;?></h6>
        </div>
        <div class="card-body">
            <?php
            echo form_open('',[],['do' => 'submit']);
            ?>
            <div class="form-group">
                <label><?php echo lang('Admin.form.subject');?></label>
                <input type="text" name="subject" class="form-control" value="<?php echo esc(set_value('subject', $template->subject));?>">
            </div>
            <div class="form-group">
                <textarea name="message" id="messageBox"><?php echo set_value('message', $template->message);?></textarea>
            </div>
            <div class="form-group">
                <button class="btn btn-primary"><?php echo lang('Admin.form.save');?></button>
                <a href="<?php echo site_url(route_to('staff_email_templates'));?>" class="btn btn-secondary"><?php echo lang('Admin.form.goBack');?></a>
            </div>
            <?php
            echo form_close();
            ?>
        </div>
    </div>
<?php
$this->endSection();
$this->section('script_block');
include __DIR__.'/tinymce.php';
$this->endSection();