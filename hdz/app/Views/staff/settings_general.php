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
            <span class="text-uppercase page-subtitle"><?php echo lang('Admin.settings.menu');?></span>
            <h3 class="page-title"><?php echo lang('Admin.settings.general');?></h3>
        </div>
    </div>
    <!-- End Page Header -->


<div class="row">
    <div class="col-lg-6">
        <div class="card mb-3">
            <div class="card-header border-bottom">
                <h6 class="mb-0"><?php echo lang('Admin.settings.general');?></h6>
            </div>
            <div class="card-body">
                <?php
                if(isset($error_msg)){
                    echo '<div class="alert alert-danger">'.$error_msg.'</div>';
                }
                if(isset($success_msg)){
                    echo '<div class="alert alert-success">'.$success_msg.'</div>';
                }
                echo form_open_multipart('',[],['action' => 'updateConfig']);
                ?>
                <div class="form-group">
                    <label><?php echo lang('Admin.settings.brandName');?></label>
                    <input type="text" name="site_name" class="form-control" value="<?php echo set_value('site_name', site_config('site_name'));?>" required>
                    <small class="form-text text-muted"><?php echo lang('Admin.settings.brandNameDescription');?></small>
                </div>

                <div class="form-group">
                    <label><?php echo lang('Admin.settings.windowTitle');?></label>
                    <input type="text" name="windows_title" class="form-control" value="<?php echo set_value('windows_title', site_config('windows_title'));?>" required>
                    <small class="form-text text-muted"><?php echo lang('Admin.settings.windowTitleDescription');?></small>
                </div>
                <div class="form-group">
                    <label><?php echo lang('Admin.settings.pageSize');?></label>
                    <input type="number" step="1" name="page_size" class="form-control" value="<?php echo set_value('page_size', site_config('page_size'));?>" required>
                    <small class="form-text text-muted"><?php echo lang('Admin.settings.pageSizeDescription');?></small>
                </div>
                <div class="form-group">
                    <label><?php echo lang('Admin.settings.dateFormat');?></label>
                    <input type="text" name="date_format" class="form-control" value="<?php echo set_value('date_format', site_config('date_format'));?>" required>
                    <small class="text-muted form-text"><?php echo lang_replace('Admin.settings.dateFormatDescription', ['[a]' => '<a href="https://php.net/manual/en/function.date.php" target="_blank">', '[/a]' => '</a>']);?></small>
                </div>
                <div class="form-group">
                    <label><?php echo lang('Admin.settings.defaultTimezone');?></label>
                    <select name="timezone" class="form-control custom-select">
                        <option value=""><?php echo lang('Admin.settings.defaultServerTimezone');?></option>
                        <?php
                        $default = set_value('timezone', site_config('timezone'));
                        foreach (timezone_identifiers_list() as $k){
                            if($default == $k){
                                echo '<option value="'.$k.'" selected>'.$k.'</option>';
                            }else{
                                echo '<option value="'.$k.'">'.$k.'</option>';
                            }
                        }
                        ?>
                    </select>
                    <small class="form-text text-muted"><?php echo lang('Admin.settings.defaultTimezoneDescription');?></small>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary"><?php echo lang('Admin.form.save');?></button>
                </div>
                <?php
                echo form_close();
                ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card mb-3">
            <div class="card-header border-bottom">
                <h6 class="mb-0"><?php echo lang('Admin.settings.changeLogo');?></h6>
            </div>
            <div class="card-body">
                <?php
                if(isset($logo_error)){
                    echo '<div class="alert alert-danger">'.$logo_error.'</div>';
                }
                if(isset($logo_success)){
                    echo '<div class="alert alert-success">'.$logo_success.'</div>';
                }
                echo form_open('',['id'=>'logoForm'],['action'=>'deleteLogo']);
                echo form_close();
                echo form_open_multipart('',[],['action' => 'uploadLogo']);
                ?>
                <div class="form-group">
                    <img src="<?php echo site_logo();?>" style="max-height: 50px" class="img-fluid">
                    <?php
                    if(site_config('logo') != ''){
                        echo '<div><button type="button" class="btn btn-link" onclick="$(\'#logoForm\').submit();"><i class="far fa-trash-alt"></i> Delete logo</button></div>';
                    }
                    ?>
                </div>
                <div class="form-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="logo" id="logoFile">
                        <label class="custom-file-label" for="logoFile" data-browse="<?php echo lang('Admin.form.browse');?>"><?php echo lang('Admin.form.chooseFile');?></label>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary"><?php echo lang('Admin.form.submit');?></button>
                </div>
                <?php
                echo form_close();
                ?>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header border-bottom">
                <h6 class="mb-0"><?php echo lang('Admin.settings.maintenanceMode');?></h6>
            </div>
            <div class="card-body">
                <?php
                if(isset($maintenance_error)){
                    echo '<div class="alert alert-danger">'.$maintenance_error.'</div>';
                }
                if(isset($maintenance_success)){
                    echo '<div class="alert alert-success">'.$maintenance_success.'</div>';
                }
                echo form_open('',['id'=>'logoForm'],['action'=>'update_maintenance']);
                ?>
                <div class="form-group">
                    <label><?php echo lang('Admin.form.status');?></label>
                    <select name="maintenance" class="custom-select">
                        <?php
                        $default = set_value('maintenance', site_config('maintenance'));
                        foreach (['0' => lang('Admin.form.disable'),'1'=>lang('Admin.form.enable')] as $k => $v){
                            if($k == $default){
                                echo '<option value="'.$k.'" selected>'.$v.'</option>';
                            }else{
                                echo '<option value="'.$k.'">'.$v.'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label><?php echo lang('Admin.settings.maintenanceMessage');?></label>
                    <textarea id="messageBox" class="form-control" name="maintenance_message"><?php echo set_value('maintenance_message', site_config('maintenance_message'));?></textarea>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary"><?php echo lang('Admin.form.save');?></button>
                </div>
                <?php
                echo form_close();
                ?>
            </div>
        </div>
    </div>
</div>
<?php
$this->endSection();
$this->section('script_block');
include __DIR__.'/tinymce.php';
$this->endSection();
