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
            <span class="text-uppercase page-subtitle">HelpDeskZ</span>
            <h3 class="page-title"><?php echo lang('Admin.agents.menu');?></h3>
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
echo form_open('',['id'=>'manageForm'],['do'=>'remove']).
    '<input type="hidden" name="agent_id" id="agent_id">'.
    form_close();
?>
    <div class="card mb-3">
        <div class="card-header">
            <div class="row">
                <div class="col d-none d-sm-block">
                    <h6 class="m-0"><?php echo lang('Admin.form.manage');?></h6>
                </div>
                <div class="col text-md-right">
                    <a href="<?php echo site_url(route_to('staff_agents_new'));?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo lang('Admin.agents.new');?></a>
                </div>
            </div>

        </div>
        <div class="table-responsive">
            <table  class="table table-striped table-hover">
                <thead class="titles">
                <tr>
                    <th><?php echo lang('Admin.form.username');?></th>
                    <th><?php echo lang('Admin.form.fullName');?></th>
                    <th><?php echo lang('Admin.agents.registration');?></th>
                    <th><?php echo lang('Admin.agents.lastLogin');?></th>
                    <th><?php echo lang('Admin.form.status');?></th>
                    <th></th>
                </tr>
                </thead>
                <?php
                foreach ($agents_list as $agent){
                    ?>
                    <tr>
                        <td>
                            <?php echo $agent->username;?><br>
                            <small><?php echo ($agent->admin ? '<span class="text-danger">'.lang('Admin.agents.administrator').'</span>' : lang('Admin.agents.agent'));?></small>
                        </td>
                        <td>
                            <?php echo $agent->fullname.'<br><small>'.$agent->email.'</small>';?>
                        </td>
                        <td><?php echo time_ago($agent->registration);?></td>
                        <td>
                            <?php echo $agent->last_login == 0 ? lang('Admin.form.never') : time_ago($agent->last_login);?>
                        </td>
                        <td>
                            <?php echo ($agent->active ? '<span class="text-success">'.lang('Admin.form.active').'</span>' : lang('Admin.form.locked'));?>
                        </td>
                        <td>
                            <?php
                            if($agent->id != staff_data('id')){
                                ?>
                                <div class="btn-group">
                                    <a href="<?php echo site_url(route_to('staff_agents_edit', $agent->id));?>" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                                    <button type="button" onclick="removeAgent(<?php echo $agent->id;?>)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                </div>
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
    </div>
<?php
$this->endSection();