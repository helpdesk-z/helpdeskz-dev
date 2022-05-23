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
            <span class="text-uppercase page-subtitle"><?php echo lang('Admin.cannedResponses.menu');?></span>
            <h3 class="page-title"><?php echo isset($canned) ? lang('Admin.cannedResponses.edit') : lang('Admin.cannedResponses.new');?></h3>
        </div>
    </div>
    <!-- End Page Header -->

<div class="card">
    <div class="card-body">
        <?php
        if(isset($error_msg)){
            echo '<div class="alert alert-danger">'.$error_msg.'</div>';
        }
        if(isset($success_msg)){
            echo '<div class="alert alert-success">'.$success_msg.'</div>';
        }
        echo form_open('',[],['do' => 'submit']);
        if(isset($canned)){
            if($canned->date > 0){
                ?>
                <div class="form-group">
                    <label><?php echo lang('Admin.form.dateCreated');?></label>
                    <input class="form-control" value="<?php echo dateFormat($canned->date);?>" readonly>
                </div>
                <?php
            }
            if($canned->last_update > 0){
                ?>
                <div class="form-group">
                    <label><?php echo lang('Admin.form.lastUpdate');?></label>
                    <input class="form-control" value="<?php echo dateFormat($canned->last_update);?>" readonly>
                </div>
                <?php
            }
            if(isset($staff_canned)){
                ?>
                <div class="form-group">
                    <label><?php echo lang('Admin.form.createdBy');?></label>
                    <input type="text" class="form-control" value="<?php echo $staff_canned->fullname;?>" readonly>
                </div>
                <?php

            }
        }
        ?>
        <div class="form-group">
            <label><?php echo lang('Admin.form.title');?></label>
            <input type="text" name="title" class="form-control" value="<?php echo set_value('title', isset($canned) ? $canned->title : '');?>">
        </div>
        <div class="form-group">
            <label><?php echo lang('Admin.form.message');?></label>
            <textarea name="message" id="messageBox" class="form-control"><?php echo set_value('message', isset($canned) ? $canned->message : '');?></textarea>
        </div>
        <div class="form-group">
            <label><?php echo lang('Admin.form.specialTags');?></label>
            <ul>
                <li>{{NAME}} : <i><?php echo lang('Admin.form.clientName');?></i></li>
                <li>{{EMAIL}} : <i><?php echo lang('Admin.form.clientEmail');?></i></li>
            </ul>
        </div>
        <div class="form-group">
            <button class="btn btn-primary"><?php echo lang('Admin.form.submit');?></button>
            <a href="<?php echo site_url(route_to('staff_canned'));?>" class="btn btn-secondary"><?php echo lang('Admin.form.goBack');?></a>
        </div>
        <?php
        echo form_close();
        ?>
    </div>
</div>
<?php
$this->endSection();
$this->section('script_block');
include  __DIR__.'/tinymce.php';
$this->endSection();