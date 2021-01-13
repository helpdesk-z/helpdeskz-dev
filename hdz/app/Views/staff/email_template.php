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
            <h3 class="page-title"><?php echo lang('Admin.settings.emailTemplates');?></h3>
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
<input type="hidden" name="action" value="change_status">
<input type="hidden" name="email_id" id="email_id">
<?php
echo form_close();
?>
    <div class="card">
        <div class="card-header">
            <?php echo lang('Admin.form.manage');?>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th><?php echo lang('Admin.form.email');?></th>
                    <th><?php echo lang('Admin.form.lastUpdate');?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php
                if(!isset($emailsList)){
                    ?>
                    <tr>
                        <td colspan="5"><?php echo lang('records_not_found');?></td>
                    </tr>
                    <?php
                }else{
                    foreach ($emailsList as $item){
                        ?>
                        <tr>
                            <td>
                                <a href="<?php echo site_url(route_to('staff_email_templates_edit', $item->id));?>"><?php echo $item->name;?></a>
                            </td>
                            <td><?php echo ($item->last_update == 0 ? '-' : time_ago($item->last_update));?></td>
                            <td>
                                <div class="btn-group btn">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="checkBox<?php echo $item->id;?>" <?php echo ($item->status > 0 ? 'checked' : '');?> onchange="changeEmailStatus('<?php echo $item->id;?>');" <?php echo ($item->status > 1 ? 'disabled' : '');?>>
                                        <label class="custom-control-label" for="checkBox<?php echo $item->id;?>"><?php echo ($item->status > 0 ? lang('Admin.form.enable') : lang('Admin.form.disable'));?></label>
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