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
            <span class="text-uppercase page-subtitle"><?php echo lang('Api.configuration');?></span>
            <h3 class="page-title"><?php echo isset($api_info) ? lang('Api.edit') : lang('Api.new');?></h3>
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
            if(isset($api_info))
            {
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo lang('Admin.form.dateCreated');?></label>
                            <input type="text" class="form-control" value="<?php echo dateFormat($api_info->date);?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo lang('Admin.form.lastUpdate');?></label>
                            <input type="text" class="form-control" value="<?php echo dateFormat($api_info->last_update);?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label><?php echo lang('Api.token');?></label>
                    <input type="text" value="<?php echo $api_info->token;?>" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="new_token" class="custom-control-input" id="getToken" value="1">
                        <label class="custom-control-label" for="getToken"><?php echo lang('Api.generateToken');?></label>
                    </div>
                </div>

            <?php
            }
            ?>
            <div class="form-group">
                <label><?php echo lang('Api.name');?></label>
                <input type="text" name="name" class="form-control" value="<?php echo set_value('name', isset($api_info) ? $api_info->name : '');?>" maxlength="200">
            </div>
            <h5><?php echo lang('Api.permissions');?></h5>
            <div class="row">
                <?php
                $options = isset($api_info) ? unserialize($api_info->permissions) : array();
                $options = is_array($options) ? $options : array();
                foreach ($api_permissions as $item => $detail){
                    ?>
                        <div class="col-md-4">
                            <h6><?php echo $detail['name'];?></h6>
                            <div class="form-group">
                                <?php
                                foreach ($detail['options'] as $key => $val){
                                    $default = set_value($item.'['.$key.']', isset($options[$item][$key]) ? $options[$item][$key] : 0);
                                    ?>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="<?php echo $item;?>[<?php echo $key;?>]" class="custom-control-input" id="<?php echo $item.'_'.$key;?>" value="1" <?php echo($default == 1 ? 'checked': '');?>>
                                        <label class="custom-control-label" for="<?php echo $item.'_'.$key;?>"><?php echo $val;?></label>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    <?php
                }
                ?>
            </div>
            <div class="form-group">
                <label><?php echo lang('Api.ipAllowed');?></label>
                <input type="text" class="form-control" name="ip_address" value="<?php echo set_value('ip_address', (isset($api_info) ? $api_info->ip_address : ''));?>">
                <small class="form-text text-muted"><?php echo lang('Api.ipAllowedDescription');?></small>
            </div>
            <div class="form-group">
                <label><?php echo lang('Admin.form.status');?></label>
                <select name="active" class="custom-select">
                    <?php
                    $default = set_value('active', (isset($api_info) ? $api_info->active : 1));
                    foreach ([0 => lang('Admin.form.disable'), 1 => lang('Admin.form.enable')] as $k => $v){
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
                <button class="btn btn-primary"><?php echo lang('Admin.form.submit');?></button>
                <a href="<?php echo site_url(route_to('staff_api'));?>" class="btn btn-secondary"><?php echo lang('Admin.form.goBack');?></a>
            </div>
            <?php
            echo form_close();
            ?>
        </div>
    </div>
<?php
$this->endSection();
