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
            <h3 class="page-title"><?php echo lang('Api.configuration');?></h3>
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
    '<input type="hidden" name="api_id" id="api_id">'.
    form_close();
?>
    <div class="card">

        <div class="card-header">
            <div class="row">
                <div class="col d-none d-sm-block">
                    <h6 class="mb-0"><?php echo lang('Admin.form.manage');?></h6>
                </div>
                <div class="col text-md-right">
                    <a href="<?php echo site_url(route_to('staff_api_new'));?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo lang('Api.new');?></a>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table  class="table table-striped table-hover">
                <thead class="titles">
                <tr>
                    <th><?php echo lang('Api.name');?></th>
                    <th><?php echo lang('Api.token');?></th>
                    <th><?php echo lang('Admin.form.status');?></th>
                    <th></th>
                </tr>
                </thead>
                <?php
                if(count($api_list) > 0){
                    foreach ($api_list as $item){
                        ?>
                        <tr>
                            <td><?php echo $item->name;?></td>
                            <td>
                                <?php echo $item->token;?>
                            </td>
                            <td><?php
                            if($item->active == 1){
                                echo '<span class="badge badge-success">'.lang('Admin.form.enable').'</span>';
                            }else{
                                echo '<span class="badge badge-danger">'.lang('Admin.form.disable').'</span>';
                            }
                                ?></td>
                            <td class="text-right">
                                <div class="btn-group">
                                    <?php
                                    echo '<a href="'.site_url(route_to('staff_api_edit', $item->id)).'" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
                                    echo ' <button type="button" onclick="removeAPI('.$item->id.')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                }else{
                    ?>
                    <tr>
                        <td colspan="5"><?php echo lang('Admin.error.recordsNotFound');?></td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
    </div>
<?php
$this->endSection();
$this->section('script_block');
?>
    <script>
        var langApiConfirmation = '<?php echo addcslashes(lang('Api.removeConfirm'), "'");?>';
        function removeAPI(msgID)
        {
            Swal.fire({
                text: langApiConfirmation,
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: langDelete,
                cancelButtonText: langCancel,
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.value) {
                    $("#api_id").val(msgID);
                    $('#manageForm').submit();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    $('#user_id').val('');
                    return false;
                }
            });
        }
    </script>
<?php
$this->endSection();