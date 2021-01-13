<?php
/**
 * @var $this \CodeIgniter\View\View
 * @var $pager \CodeIgniter\Pager\Pager
 */
$this->extend('client/template');
$this->section('window_title');
echo lang_replace('Client.viewTickets.ticketID',['%id%' => $ticket->id]);
$this->endSection();
$this->section('script_block');
?>
    <script type="text/javascript" src="<?php echo base_url('assets/components/bs-custom-file-input/bs-custom-file-input-min.js');?>"></script>
    <script>
        $(function(){
            $(document).ready(function () {
                bsCustomFileInput.init();
            });
        })
    </script>
<?php
$this->endSection();
$this->section('content');
?>
    <div class="container mt-5">
        <h1 class="heading mb-5">
            <?php echo lang('Client.viewTickets.title');?>
        </h1>

        <div class="card mb-3">
            <div class="card-body">
                <h4 class="card-title">[#<?php echo $ticket->id;?>] <?php echo esc($ticket->subject);?></h4>
                <div class="text-muted">
                    <i class="fa fa-calendar"></i> <?php echo dateFormat($ticket->date);?> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    <i class="fa fa-calendar"></i> <?php echo dateFormat($ticket->last_update);?>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label><?php echo lang('Client.form.department');?></label>
                            <input type="text" value="<?php echo $ticket->department_name;?>" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label><?php echo lang('Client.form.status');?></label>
                            <input type="text" value="<?php echo $ticket_status;?>" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label><?php echo lang('Client.form.priority');?></label>
                            <input type="text" value="<?php echo $ticket->priority_name;?>" class="form-control" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End ticket info -->
        <?php
        if(isset($error_msg)){
            echo '<div class="alert alert-danger">'.$error_msg.'</div>';
        }
        if($ticket->status == 5){
            echo '<div class="alert alert-info">'.lang('Client.viewTickets.ticketClosed').'</div>';
        }
        ?>

        <div class="mb-3">
            <div id="replyButtons" <?php echo !isset($error_msg) ? '' : 'style="display:none;"';?>>
                <button class="btn btn-primary" type="button" onclick="$('#replyButtons').hide(); $('#replyForm').show();"><i class="fa fa-edit"></i> <?php echo lang('Client.form.addReply');?></button>
                <a href="<?php echo site_url(route_to('view_tickets'));?>" class="btn btn-secondary"><?php echo lang('Client.form.goBack');?></a>
            </div>

            <?php
            echo form_open_multipart('',['id'=>'replyForm','style'=>(!isset($error_msg) ? 'display:none;' : '')],['do' => 'reply']);
            ?>
            <div class="form-group">
                <label><?php echo lang('Client.form.yourMessage');?></label>
                <textarea class="form-control" name="message" rows="5"><?php echo set_value('message');?></textarea>
            </div>
            <?php
            if(site_config('ticket_attachment')){
                ?>
                <div class="form-group">
                    <label><?php echo lang('Client.form.attachments');?></label>
                    <?php
                    for($i=1;$i<=site_config('ticket_attachment_number');$i++){
                        ?>
                        <div class="custom-file mb-2">
                            <input type="file" class="custom-file-input" name="attachment[]" id="customFile<?php echo $i;?>">
                            <label class="custom-file-label" for="customFile<?php echo $i;?>" data-browse="<?php echo lang('Client.form.browse');?>"><?php echo lang('Client.form.chooseFile');?></label>
                        </div>
                        <?php
                    }
                    ?>
                    <small class="text-muted"><?php echo lang('Client.Form.allowedFiles');?> <?php echo '*.'.implode(', *.', unserialize(site_config('ticket_file_type')));?></small>
                </div>
                <?php
            }
            ?>

            <div class="form-group">
                <button class="btn btn-primary"><i class="fa fa-paper-plane"></i> <?php echo lang('Client.form.submit');?></button>
                <a href="<?php echo site_url(route_to('view_tickets'));?>" class="btn btn-secondary"><?php echo lang('Client.form.goBack');?></a>
            </div>
            <?php
            echo form_close();
            ?>
        </div>
        <?php
        if(\Config\Services::session()->has('form_success')){
            echo '<div class="alert alert-success">'.\Config\Services::session()->getFlashdata('form_success').'</div>';
        }
        ?>
        <?php
        if($pager->getPageCount() > 1){
            echo $pager->links();
        }
        ?>
        <?php if(isset($result_data)):?>
            <?php foreach ($result_data as $item):?>
                <div class="card mb-3 <?php echo ($item->customer == 1 ? '' : 'bg-staff');?>">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-2 col-lg-3">
                                <?php
                                if($item->customer == 1){
                                    ?>
                                    <div class="text-center">
                                        <div class="mb-3">
                                            <img src="<?php echo user_avatar($ticket->avatar);?>" class="user-avatar rounded-circle img-fluid" style="max-width: 100px">
                                        </div>
                                        <div class="mb-3">
                                            <div><?php echo $ticket->fullname;?></div>
                                            <?php
                                            echo '<span class="badge badge-dark">'.lang('Client.form.user').'</span>';
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                }else{
                                    $staffData = staff_info($item->staff_id);
                                    ?>
                                    <div class="text-center">
                                        <div class="mb-3">
                                            <img src="<?php echo $staffData['avatar'];?>" class="user-avatar rounded-circle img-fluid" style="max-width: 100px">
                                        </div>
                                        <div class="mb-3">
                                            <div><?php echo $staffData['fullname'];?></div>
                                            <?php
                                            echo '<span class="badge badge-primary">'.lang('Client.form.staff').'</span>';
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <small class="text-muted"><i class="fa fa-calendar"></i> <?php echo dateFormat($item->date);?></small>
                                </div>
                                <div id="msg_<?php echo $item->id;?>" class="form-group">
                                    <?php echo ($item->email == 1 ? $item->message : nl2br($item->message));?>
                                </div>
                                <?php
                                if($files = ticket_files($ticket->id, $item->id)){
                                    ?>
                                    <div class="alert alert-info">
                                        <p class="font-weight-bold"><?php echo lang('Client.form.attachments');?></p>
                                        <?php foreach ($files as $file):?>
                                            <div class="form-group">
                                                <span class="knowledgebaseattachmenticon"></span>
                                                <i class="fa fa-file-archive-o"></i> <a href="<?php echo current_url().'?download='.$file->id;?>" target="_blank"><?php echo $file->name;?></a>
                                                <?php echo number_to_size($file->filesize,2);?>
                                            </div>
                                        <?php endforeach;?>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach;?>
        <?php endif;?>
        <?php
        if($pager->getPageCount() > 1){
            echo $pager->links();
        }
        ?>
    </div>
<?php
$this->endSection();