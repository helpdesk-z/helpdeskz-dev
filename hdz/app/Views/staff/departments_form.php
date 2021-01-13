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
            <span class="text-uppercase page-subtitle"><?php echo lang('Admin.tickets.departments');?></span>
            <h3 class="page-title"><?php echo isset($department) ? lang('Admin.tickets.editDepartment') : lang('Admin.tickets.newDepartment');?></h3>
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
        <div class="card-body">
            <?php
            echo form_open('',[],['do' => 'submit']);
            ?>
            <div class="form-group">
                <label><?php echo lang('Admin.form.department');?></label>
                <input type="text" name="name" class="form-control" value="<?php echo set_value('name', isset($department) ? $department->name : '');?>">
            </div>
            <div class="form-group">
                <label><?php echo lang('Admin.form.type');?></label>
                <select name="private" class="form-control custom-select">
                    <?php
                    $default = set_value('private', isset($department) ? $department->private : 0);
                    foreach (['0' => lang('Admin.form.public'),'1' => lang('Admin.form.private')] as $k => $v){
                        if($k == $default){
                            echo '<option value="'.$k.'" selected>'.$v.'</option>';
                        }else{
                            echo '<option value="'.$k.'">'.$v.'</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <?php if(!isset($department) || (isset($department) && count($list_departments) > 1)):?>
                <div class="form-group">
                    <label><?php echo lang('Admin.form.displayOrder');?></label>
                    <select name="position" class="form-control custom-select">
                        <?php
                        if(isset($department)){
                            echo '<option value="">'.lang('Admin.form.notModify').'</option>';
                        }
                        ?>
                        <option value="start"><?php echo lang('Admin.form.beginningList');?></option>
                        <option value="end"><?php echo lang('Admin.form.endList');?></option>
                        <?php
                        $default = set_value('position', isset($department) ? $department->dep_order : '');
                        if(isset($list_departments)){
                            foreach ($list_departments as $item){
                                if(!isset($department) || (isset($department) && $department->id != $item->id)){
                                    echo '<option value="'.$item->id.'">'.lang_replace('Admin.form.afterItem',['%item%' => $item->name]).'</option>';
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
            <?php endif;?>
            <div class="form-group">
                <button class="btn btn-primary"><?php echo lang('Admin.form.submit');?></button>
                <a href="<?php echo site_url(route_to('staff_departments'));?>" class="btn btn-secondary"><?php echo lang('Admin.form.goBack');?></a>
            </div>
            <?php
            echo form_close();
            ?>
        </div>
    </div>
<?php
$this->endSection();