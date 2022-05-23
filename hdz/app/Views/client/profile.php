<?php
/**
 * @var $this \CodeIgniter\View\View
 */
$this->extend('client/template');
$this->section('window_title');
echo lang('Client.account.editProfile');
$this->endSection();
$this->section('content');
?>
<div class="container mt-5">
    <h1 class="heading mb-5">
        <?php echo lang('Client.account.editProfile');?>
    </h1>
    <div class="mb-3">
        <?php
        if(isset($error_msg)){
            echo '<div class="alert alert-danger">'.$error_msg.'</div>';
        }
        if(\Config\Services::session()->has('form_success')){
            echo '<div class="alert alert-success">'.\Config\Services::session()->getFlashdata('form_success').'</div>';
        }
        ?>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true"><?php echo lang('Client.account.general');?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="password-tab" data-toggle="tab" href="#password" role="tab" aria-controls="password" aria-selected="false"><?php echo lang('Client.account.changePassword');?></a>
            </li>
        </ul>
        <div class="tab-content pt-4" id="myTabContent">
            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                <?php echo form_open('',[],['do'=>'general']);?>
                <div class="form-group">
                    <label><?php echo lang('Client.form.fullName');?></label>
                    <input type="text" name="fullname" value="<?php echo set_value('fullname', client_data('fullname'));?>" class="form-control">
                </div>
                <div class="form-group">
                    <label><?php echo lang('Client.form.email');?></label>
                    <input type="text" name="email" value="<?php echo set_value('email', client_data('email'));?>" class="form-control">
                </div>
                <div class="form-group">
                    <label><?php echo lang('Client.form.timezone');?></label>
                    <select name="timezone" class="searcher">
                        <option value="">-- <?php echo lang('Client.form.defaultTimezone');?> --</option>
                        <?php
                        $timezone = timezone_identifiers_list();
                        foreach ($timezone as $item){
                            if(client_data('timezone') == $item){
                                echo '<option value="'.$item.'" selected>'.$item.'</option>';
                            }else{
                                echo '<option value="'.$item.'">'.$item.'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary"><?php echo lang('Client.form.save');?></button>
                </div>
                <?php echo form_close();?>
            </div>
            <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                <?php echo form_open('',[],['do'=>'password']);?>
                <div class="form-group">
                    <label><?php echo lang('Client.form.existingPassword');?></label>
                    <input type="password" name="current_password" class="form-control">
                </div>
                <div class="form-group">
                    <label><?php echo lang('Client.form.newPassword');?></label>
                    <input type="password" name="new_password" class="form-control">
                </div>
                <div class="form-group">
                    <label><?php echo lang('Client.form.newPassword');?> (<?php echo lang('Client.form.confirm');?>)</label>
                    <input type="password" name="new_password2" class="form-control">
                </div>
                <div class="form-group">
                    <button class="btn btn-primary"><?php echo lang('Client.form.save');?></button>
                </div>
                <?php echo form_close();?>
            </div>
        </div>



    </div>
</div>
<?php
$this->endSection();