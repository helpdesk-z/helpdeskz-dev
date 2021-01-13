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
            <span class="text-uppercase page-subtitle"><?php echo lang('Admin.account.menu');?></span>
            <h3 class="page-title"><?php echo lang('Admin.account.profile');?></h3>
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
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs border-bottom" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true"><?php echo lang('Admin.form.general');?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="password-tab" data-toggle="tab" href="#password" role="tab" aria-controls="password" aria-selected="false"><?php echo lang('Admin.account.changePassword');?></a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="row">
                    <div class="col-lg-3 text-center">
                        <div class="form-group">
                            <img src="<?php echo staff_avatar(staff_data('avatar'));?>" class="user-avatar rounded-circle" style="max-width: 100px">
                        </div>
                        <?php
                        if(staff_data('avatar') != ''){
                            echo form_open('',[],['do'=>'delete_avatar']);
                            echo '<div class="form-group"><button class="btn btn-primary">'.lang('Admin.form.deleteAvatar').'</button></div>';
                            echo form_close();
                        }
                        ?>
                    </div>
                    <div class="col">
                        <?php echo form_open_multipart('',[],['do'=>'update_profile']);?>
                        <div class="form-group">
                            <label><?php echo lang('Admin.form.avatar');?></label>
                            <div class="custom-file">
                                <input type="file" name="avatar" class="custom-file-input" id="customFile">
                                <label class="custom-file-label" for="customFile" data-browse="<?php echo lang('Admin.form.browse');?>"><?php echo lang('Admin.form.chooseFile');?></label>
                            </div>
                            <small class="form-text text-muted"><?php echo lang('Admin.settings.allowedFileTypes');?>: .jpg, .gif, .png. <strong><?php echo lang('Admin.settings.maxUploadSize');?></strong>: <?php echo number_to_size(max_file_size()*1024);?></small>
                        </div>

                        <div class="form-group">
                            <label><?php echo lang('Admin.form.fullName');?></label>
                            <input type="text" name="fullname" value="<?php echo staff_data('fullname');?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('Admin.account.timezone');?></label>
                            <select name="timezone" class="form-control custom-select">
                                <option value="">-- <?php echo lang('Admin.settings.defaultTimezone');?> --</option>
                                <?php foreach (timezone_identifiers_list() as $k):?>
                                    <?php if($k == staff_data('timezone')):?>
                                        <option value="<?php echo $k;?>" selected><?php echo $k;?></option>
                                    <?php else:?>
                                        <option value="<?php echo $k;?>"><?php echo $k;?></option>
                                    <?php endif;?>
                                <?php endforeach;?>
                            </select>
                            <small class="text-muted form-text"><?php echo lang('Admin.account.timezoneDescription');?></small>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('Admin.form.email');?></label>
                            <input type="email" name="email" value="<?php echo staff_data('email');?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('Admin.form.signature');?></label>
                            <textarea class="form-control" name="signature" id="messageBox"><?php echo staff_data('signature');?></textarea>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary"><?php echo lang('Admin.form.save');?></button>
                        </div>
                        <?php echo form_close();?>
                    </div>
                </div>

            </div>
            <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                <?php echo form_open('',[],['do'=>'update_password']);?>
                <div class="form-group">
                    <label><?php echo lang('Admin.form.existingPassword');?></label>
                    <input type="password" name="current_password" class="form-control">
                    <small class="text-muted form-text"><?php echo lang('Admin.form.enterExistingPassword');?></small>
                </div>
                <div class="form-group">
                    <label><?php echo lang('Admin.form.newPassword');?></label>
                    <input type="password" name="new_password" class="form-control">
                    <small class="text-muted form-text"><?php echo lang('Admin.form.enterNewPassword');?></small>
                </div>
                <div class="form-group">
                    <label><?php echo lang('Admin.form.newPassword').' ('.lang('Admin.form.confirm').')';?></label>
                    <input type="password" name="new_password2" class="form-control">
                    <small class="text-muted form-text"><?php echo lang('Admin.form.enterNewPassword');?></small>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary"><?php echo lang('Admin.form.save');?></button>
                </div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>


<?php
$this->endSection();
$this->section('script_block');
include __DIR__.'/tinymce.php';
$this->endSection();