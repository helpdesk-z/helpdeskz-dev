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
            <h3 class="page-title"><?php echo lang('Admin.settings.tickets');?></h3>
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
                <label><?php echo lang('Admin.settings.displayOrderReplies');?></label>
                <select name="reply_order" class="form-control custom-select">
                    <?php
                    $default = set_value('reply_order', site_config('reply_order'));
                    foreach (['desc'=>lang('Admin.settings.newestReplyFirst'),'asc'=>lang('Admin.settings.oldestReplyFirst')] as $k => $v){
                        if($default == $k){
                            echo '<option value="'.$k.'" selected>'.$v.'</option>';
                        }else{
                            echo '<option value="'.$k.'">'.$v.'</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label><?php echo lang('Admin.settings.ticketsPerPage');?></label>
                <input type="number" step="1" min="1" name="tickets_page" class="form-control" value="<?php echo set_value('tickets_page', site_config('tickets_page'));?>">
                <small class="text-muted form-text"><?php echo lang('Admin.settings.ticketsPerPageDescription');?></small>
            </div>
            <div class="form-group">
                <label><?php echo lang('Admin.settings.repliesPerPage');?></label>
                <input type="number" step="1" min="1" name="tickets_replies" class="form-control" value="<?php echo set_value('tickets_replies', site_config('tickets_replies'));?>">
                <small class="text-muted form-text"><?php echo lang('Admin.settings.repliesPerPageDescription');?></small>
            </div>
            <div class="form-group">
                <label><?php echo lang('Admin.settings.defaultDeadline');?></label>
                <input type="number" step="1" min="1" name="overdue_time" class="form-control" value="<?php echo set_value('overdue_time',site_config('overdue_time'));?>">
                <small class="text-muted form-text"><?php echo lang('Admin.settings.defaultDeadlineDescription');?></small>
            </div>
            <div class="form-group">
                <label><?php echo lang('Admin.settings.defaultAutoClose');?></label>
                <input type="number" step="1" min="1" name="ticket_autoclose" class="form-control" value="<?php echo set_value('ticket_autoclose', site_config('ticket_autoclose'));?>">
                <small class="text-muted form-text"><?php echo lang('Admin.settings.defaultAutoCloseDescription');?></small>
            </div>
            <div class="form-group">
                <label><?php echo lang('Admin.settings.allowAttachments');?></label>
                <select name="ticket_attachment" class="form-control custom-select" id="attachments">
                    <?php
                    $default = set_value('ticket_attachment', site_config('ticket_attachment'));
                    foreach (['0' => lang('Admin.form.no'),'1'=>lang('Admin.form.yes')] as $k => $v){
                        if($k == $default){
                            echo '<option value="'.$k.'" selected>'.$v.'</option>';
                        }else{
                            echo '<option value="'.$k.'">'.$v.'</option>';
                        }
                    }
                    ?>
                </select>

            </div>
            <div id="attachments_details">
                <div class="form-group">
                    <label><?php echo lang('Admin.settings.numberAttachments');?></label>
                    <input type="number" class="form-control" name="ticket_attachment_number" value="<?php echo set_value('ticket_attachment_number', site_config('ticket_attachment_number'));?>">
                </div>
                <div class="form-group">
                    <label><?php echo lang('Admin.settings.maxUploadSize');?></label>
                    <div class="input-group">
                        <input type="text" name="ticket_file_size" value="<?php echo set_value('ticket_file_size', site_config('ticket_file_size'));?>" class="form-control">
                        <div class="input-group-append"><span class="input-group-text">MB</span></div>
                    </div>
                    <small class="text-muted form-text"><?php echo lang_replace('Admin.settings.maxUploadSizeDescription', ['%size%' => number_to_size(max_file_size()*1024)]);?></small>
                </div>
                <div class="form-group">
                    <label><?php echo lang('Admin.settings.allowedFileTypes');?></label>
                    <input type="text" name="ticket_file_type" class="form-control" value="<?php echo set_value('ticket_file_type', implode(', ', unserialize(site_config('ticket_file_type'))));?>">
                    <small class="text-muted form-text"><?php echo lang('Admin.settings.allowedFileTypesDescription');?></small>
                </div>
            </div>

            <div class="form-group">
                <button class="btn btn-primary"><?php echo lang('Admin.form.save');?></button>
            </div>
            <?php
            echo form_close();
            ?>
        </div>
    </div>
<?php
$this->endSection();
$this->section('script_block');
?>
    <script>
        $(function (){
            attachment_status();
            $('#attachments').on('change', function(){
                attachment_status();
            });
        })
        function attachment_status()
        {
            if($('#attachments').val() === '1'){
                $('#attachments_details').show();
            }else{
                $('#attachments_details').hide();
            }
        }
    </script>
<?php
$this->endSection();