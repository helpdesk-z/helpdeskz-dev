<?php
/**
 * @var $this \CodeIgniter\View\View
 */
$this->extend('staff/template');
$this->section('content');
$request = \CodeIgniter\Services::request();
?>
    <!-- Page Header -->
    <div class="page-header row no-gutters py-4">
        <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
            <span class="text-uppercase page-subtitle">HelpDeskZ</span>
            <h3 class="page-title"><?php echo lang('Admin.tickets.menu');?></h3>
        </div>
    </div>
    <!-- End Page Header -->


<div class="mb-3">
    <a href="<?php echo site_url(route_to('staff_tickets'));?>" class="btn <?php echo ($page_type == 'main' ? 'btn-primary' : 'btn-outline-primary');?>"><?php echo lang('Admin.tickets.active');?> (<?php echo count_status('active');?>)</a>
    <a href="<?php echo site_url(route_to('staff_tickets_overdue'));?>" class="btn <?php echo ($page_type == 'overdue' ? 'btn-primary' : 'btn-outline-primary');?>"><?php echo lang('Admin.form.overdue');?> (<?php echo count_status('overdue');?>)</a>
    <a href="<?php echo site_url(route_to('staff_tickets_answered'));?>" class="btn <?php echo ($page_type == 'answered' ? 'btn-primary' : 'btn-outline-primary');?>"><?php echo lang('Admin.form.answered');?> (<?php echo count_status('answered');?>)</a>
    <a href="<?php echo site_url(route_to('staff_tickets_closed'));?>" class="btn <?php echo ($page_type == 'closed' ? 'btn-primary' : 'btn-outline-primary');?>"><?php echo lang('Admin.form.closed');?> (<?php echo count_status('closed');?>)</a>
</div>

    <div class="card mb-3">
        <div class="card-header border-bottom">
            <h6 class="mb-0"><?php echo lang('Admin.form.search');?></h6>
        </div>
        <div class="card-body">

            <?php echo form_open(route_to('staff_tickets_search'),['method'=>'get']);?>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo lang('Admin.form.keyword');?></label>
                        <?php
                        echo form_input([
                            'name' => 'keyword',
                            'value' => $request->getGet('keyword'),
                            'class' => 'form-control'
                        ]);
                        ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo lang('Admin.form.dateOpened');?></label>
                        <?php
                        echo form_input([
                            'name' => 'date_created',
                            'value' => $request->getGet('date_created'),
                            'class' => 'form-control datepicker'
                        ]);
                        ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo lang('Admin.form.lastUpdate');?></label>
                        <?php
                        echo form_input([
                            'name' => 'last_update',
                            'value' => $request->getGet('last_update'),
                            'class' => 'form-control datepicker'
                        ]);
                        ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo lang('Admin.form.department');?></label>
                        <select name="department" class="form-control custom-select">
                            <option value="">-------------------</option>
                            <?php
                            if($department_list = $departments){
                                foreach ($department_list as $item){
                                    if($request->getGet('department') == $item->id){
                                        echo '<option value="'.$item->id.'" selected>'.$item->name.'</option>';
                                    }else{
                                        echo '<option value="'.$item->id.'">'.$item->name.'</option>';
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo lang('Admin.form.status');?></label>
                        <select name="status" class="form-control custom-select">
                            <option value="">-------------------</option>
                            <?php
                            foreach ($statuses as $k => $v){
                                if($request->getGet('status') == $k){
                                    echo '<option value="'.$k.'" selected>'.lang('Admin.form.'.$v).'</option>';
                                }else{
                                    echo '<option value="'.$k.'">'.lang('Admin.form.'.$v).'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" id="overdueTickets" name="overdue" <?php echo ($request->getGet('overdue') == '1' ? 'checked' : '');?>
                    <label class="form-check-label" for="overdueTickets">
                        <?php echo lang('Admin.tickets.showOverdueOnly');?>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-primary"><i class="fa fa-search"></i> <?php echo lang('Admin.form.search');?></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
    <!-- chart row starts here -->
<?php
if(isset($error_msg)){
    echo '<div class="alert alert-danger">'.$error_msg.'</div>';
}
echo form_open(current_url(true),[
    'id' => 'ticketForm'
]);
echo form_input([
    'type'=>'hidden',
    'name' => 'action',
    'value' => 'update',
    'id' => 'ticket_action'
]);
?>

    <div class="card mb-3">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-12">
                    <?php
                    $details = $pager->getDetails();
                    $perPage = $details['perPage'];
                    $showing = $perPage*$details['currentPage'];
                    $total = $details['total'];
                    $from = $showing+1-$perPage;
                    if($from > 0){
                        echo lang_replace('Admin.tickets.showingResults',[
                            '%x%' => ($showing+1-$perPage),
                            '%y%' => ($showing > $total ? $total : $showing),
                            '%z%' => $total
                        ]);
                    }else{
                        echo lang('Admin.tickets.menu');
                    }

                    ?>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th><input type="checkbox" id="select_all" /></th>
                    <th><?php echo sort_link('id',lang('Admin.tickets.id'));?></th>
                    <th><?php echo sort_link('subject',lang('Admin.form.subject'));?></th>
                    <th><?php echo sort_link('last_reply',lang('Admin.form.lastReply'));?></a></th>
                    <th><?php echo sort_link('department',lang('Admin.form.department'));?></th>
                    <th><?php echo sort_link('priority',lang('Admin.form.priority'));?></th>
                    <th><?php echo sort_link('status',lang('Admin.form.status'));?></th>
                </tr>
                </thead>
                <?php if(!$tickets_result):?>
                    <tr>
                        <td colspan="8">
                            <i><?php echo lang('Admin.error.recordsNotFound');?></i>
                        </td>
                    </tr>
                <?php else:?>
                    <?php foreach ($tickets_result as $item):?>
                        <tr <?php echo isOverdue($item->last_update, $item->status) ? 'class="table-danger"' : '';?>>
                            <td width="15">
                                <input type="checkbox" name="ticket_id[]" value="<?php echo $item->id;?>" class="select_item form-check">
                            </td>
                            <td>
                                <a href="<?php echo site_url(route_to('staff_ticket_view', $item->id));?>"><?php echo $item->id;?></a><br>
                                <small class="text-muted"><?php echo time_ago($item->date);?></small>
                            </td>
                            <td>
                                <div class="font-weight-bold">
                                    <a href="<?php echo site_url(route_to('staff_ticket_view', $item->id));?>"><?php echo resume_content($item->subject, 30);?></a>
                                </div>
                                <div class="text-muted"><i class="fa fa-user-o"></i> <?php echo $item->fullname;?></div>
                            </td>
                            <td nowrap="">
                                <?php
                                echo time_ago($item->last_update);
                                ?>
                                <?php echo ($item->last_replier == 0 ?
                                    '<div class="text-muted"><i class="fa fa-user-o"></i> '.$item->fullname.'</div>' :
                                    '<div class="text-danger"><i class="fa fa-user-secret"></i> '.$item->staff_username);
                                ?>
                            </td>

                            <td>
                                <?php echo $item->department_name;?>
                                <div class="text-muted"><i class="fa fa-commenting-o"></i> <?php echo lang_replace('Admin.tickets.totalReplies',['%number%' => $item->replies]);?></div>
                            </td>

                            <td style="color: <?php echo $item->priority_color;?>">
                                <?php echo $item->priority_name;?>
                            </td>

                            <td>
                                <?php
                                switch ($item->status){
                                    case 1:
                                        echo '<span class="badge badge-success">'.lang('Admin.form.open').'</span>';
                                        break;
                                    case 2:
                                        echo '<span class="badge badge-dark">'.lang('Admin.form.answered').'</span>';
                                        break;
                                    case 3:
                                        echo '<span class="badge badge-warning">'.lang('Admin.form.awaiting_reply').'</span>';
                                        break;
                                    case 4:
                                        echo '<span class="badge badge-info">'.lang('Admin.form.in_progress').'</span>';
                                        break;
                                    case 5:
                                        echo '<span class="badge badge-danger">'.lang('Admin.form.closed').'</span>';
                                        break;
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
            </table>
        </div>
    </div>
<?php echo $pager->links();?>

    <div id="ticket_options" style="margin-top:20px; display:none;">
        <div class="card">
            <div class="card-header"><?php echo lang('Admin.form.massAction');?></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label><?php echo lang('Admin.form.department');?></label>
                            <select name="department" class="custom-select">
                                <option value=""><?php echo lang('Admin.form.noChange');?></option>
                                <?php
                                if($departments){
                                    foreach ($departments as $item){
                                        echo '<option value="'.$item->id.'">'.$item->name.'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label><?php echo lang('Admin.form.status');?></label>
                            <select name="status" class="custom-select">
                                <option value=""><?php echo lang('Admin.form.noChange');?></option>
                                <?php
                                foreach ($statuses as $k => $v){
                                    echo '<option value="'.$k.'">'.lang('Admin.form.'.$v).'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label><?php echo lang('Admin.form.priority');?></label>
                            <select name="priority" class="custom-select">
                                <option value=""><?php echo lang('Admin.form.noChange');?></option>
                                <?php
                                if($priorities){
                                    foreach ($priorities as $item){
                                        echo '<option value="'.$item->id.'">'.$item->name.'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary"><i class="fa fa-save"></i> <?php echo lang('Admin.form.submit');?></button>
                    <button id="trash_button" type="button" class="m-0 btn btn-danger"><i class="fa fa-trash"></i> <?php echo lang('Admin.form.delete');?></button>
                </div>
            </div>
        </div>
    </div>
<?php
echo form_close();
$this->endSection();
$this->section('script_block');
?>
<script>
    $(function (){
        ticketsPage();
    });
</script>
<?php
$this->endSection();
