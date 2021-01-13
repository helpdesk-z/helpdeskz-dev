<?php
/**
 * @var $this \CodeIgniter\View\View
 */
$this->extend('client/template');
$this->section('window_title');
echo lang('Client.login.forgotPassword');
$this->endSection();
$this->section('content');
?>
<div class="container mt-5">
    <h1 class="heading mb-5">
        <?php echo lang('Client.login.forgotPassword');?>
    </h1>
    <div class="mb-3">
        <?php echo lang('Client.login.forgotDescription');?>
    </div>


    <?php
    if(\Config\Services::session()->has('form_success')){
        echo '<div class="alert alert-success">'.\Config\Services::session()->getFlashdata('form_success').'</div>';
    }
    if(isset($error_msg)){
        echo '<div class="alert alert-danger">'.$error_msg.'</div>';
    }
    echo form_open('',[],['do'=>'submit']);
    ?>
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label><?php echo lang('Client.form.email');?></label>
                <input type="email" name="email" class="form-control" value="<?php echo set_value('email');?>">
            </div>
            <?php echo $recaptcha;?>
            <div class="form-group">
                <button class="btn btn-primary"><?php echo lang('Client.form.submit');?></button>
            </div>
        </div>
    </div>
<?php
echo form_close();
?>
</div>
<?php
$this->endSection();