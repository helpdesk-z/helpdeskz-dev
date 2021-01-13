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
            <h3 class="page-title"><?php echo lang('Admin.settings.emailAddresses');?></h3>
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
echo form_open('',['id'=>'emailForm']);
?>
<input type="hidden" name="action" id="email_action">
<input type="hidden" name="email_id" id="email_id">
<?php
echo form_close();
?>
<div class="alert alert-info">
    <i class="fa fa-info-circle"></i> <?php echo lang('Admin.settings.defaultEmailAddressDescription');?>
</div>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-5">
                    <h6 class="mb-0"><?php echo lang('Admin.form.manage');?></h6>
                </div>
                <div class="col-sm-7">
                    <a href="<?php echo site_url(route_to('staff_emails_new'));?>" class="btn btn-primary btn-sm float-right"><i class="fa fa-plus"></i> <?php echo lang('Admin.settings.newEmailAddress');?></a>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th><?php echo lang('Admin.form.email');?></th>
                    <th><?php echo lang('Admin.form.department');?></th>
                    <th><?php echo lang('Admin.form.dateCreated');?></th>
                    <th><?php echo lang('Admin.form.lastUpdate');?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php
                if(!isset($emailsList)){
                    ?>
                    <tr>
                        <td colspan="5"><?php echo lang('Admin.error.recordsNotFound');?></td>
                    </tr>
                    <?php
                }else{
                    foreach ($emailsList as $item){
                        $department = getDepartmentByID($item->department_id);
                        ?>
                        <tr>
                            <td>
                                <?php
                                echo $item->name;
                                if($item->default){
                                    echo ' <span class="badge badge-dark">'.lang('Admin.form.default').'</span>';
                                }
                                ?>
                                <br>
                                <small><i class="fa fa-angle-left"></i> <?php echo $item->email;?> <i class="fa fa-angle-right"></i></small>
                            </td>
                            <td><?php echo ($department ? $department->name : '');?></td>
                            <td><?php echo time_ago($item->created);?></td>
                            <td><?php echo ($item->last_update == 0 ? '-' : time_ago($item->last_update));?></td>
                            <td>
                                <div class="dropdown ">
                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <?php echo lang('Admin.form.action');?>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="<?php echo site_url(route_to('staff_emails_edit', $item->id));?>"><i class="fa fa-edit"></i> <?php echo lang('Admin.form.edit');?></a>
                                        <?php
                                        if(!$item->default){
                                            ?>
                                            <button class="dropdown-item" onclick="removeEmail(<?php echo $item->id;?>);"><i class="far fa-trash-alt"></i> <?php echo lang('Admin.form.delete');?></button>
                                            <button class="dropdown-item" onclick="setEmailDefault(<?php echo $item->id;?>);"><i class="far fa-star"></i> <?php echo lang('Admin.form.setDefault');?></button>
                                                <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
<?php
$this->endSection();