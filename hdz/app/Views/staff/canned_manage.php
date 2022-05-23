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
            <h3 class="page-title"><?php echo lang('Admin.cannedResponses.menu');?></h3>
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
    '<input type="hidden" name="msgID" id="cannedID">'.
    form_close();
?>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col d-none d-sm-block">
                    <?php echo lang('Admin.form.manage');?>
                </div>
                <div class="col text-md-right">
                    <a href="<?php echo site_url(route_to('staff_new_canned'));?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo lang('Admin.cannedResponses.new');?></a>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table  class="table table-striped table-hover">
                <?php
                if(isset($cannedList)){
                    foreach ($cannedList as $item){
                        ?>
                        <tr>
                            <td><?php echo $item->title;?></td>
                            <td class="text-right">
                                <div class="btn-group">
                                    <?php
                                    if(staff_data('admin')){
                                        if($item->position != 1){
                                            echo '<a href="'.site_url(route_to('staff_canned')).'?action=move_up&msgID='.$item->id.'" class="btn btn-outline-dark btn-sm"><i class="fa fa-chevron-up"></i></a>';
                                        }else{
                                            echo '<a href="#" class="btn btn-outline-light btn-sm disabled"><i class="fa fa-chevron-up"></i></a>';
                                        }
                                        if($item->position != $lastCannedPosition){
                                            echo '<a href="'.site_url(route_to('staff_canned')).'?action=move_down&msgID='.$item->id.'" class="btn btn-outline-dark btn-sm"><i class="fa fa-chevron-down"></i></a>';
                                        }else{
                                            echo '<a href="#" class="btn btn-outline-light btn-sm disabled"><i class="fa fa-chevron-down"></i></a>';
                                        }
                                    }

                                    ?>
                                </div>
                                <div class="btn-group">
                                    <?php
                                    if(staff_data('admin') || $item->staff_id == staff_data('id')){
                                        echo '<a href="'.site_url(route_to('staff_canned_edit', $item->id)).'" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
                                        echo '<button type="button" onclick="removeCannedResponse('.$item->id.')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                }else{
                    ?>
                    <tr>
                        <td colspan="2"><?php echo lang('Admin.error.recordsNotFound');?></td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
    </div>
<?php
$this->endSection();