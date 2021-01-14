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
            <span class="text-uppercase page-subtitle"><?php echo lang('Admin.settings.emailAddresses');?></span>
            <h3 class="page-title"><?php echo isset($email) ? lang('Admin.settings.editEmailAddress') : lang('Admin.settings.newEmailAddress');?></h3>
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
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs border-bottom" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general-content" role="tab" aria-selected="true"><?php echo lang('Admin.form.general');?></a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="outgoing-tab" data-toggle="tab" href="#outgoing-content" role="tab" aria-controls="profile" aria-selected="false"><?php echo lang('Admin.settings.outgoing');?></a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="incoming-tab" data-toggle="tab" href="#incoming-content" role="tab" aria-controls="contact" aria-selected="false"><?php echo lang('Admin.settings.incoming');?></a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <?php
            echo form_open('',[],['do' => 'submit']);
            ?>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="general-content" role="tabpanel" aria-labelledby="general-tab">
                    <div class="form-group">
                        <label><?php echo lang('Admin.form.email');?></label>
                        <input type="email" name="email" class="form-control" value="<?php echo set_value('email', (isset($email) ? $email->email : ''));?>">
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('Admin.form.emailName');?></label>
                        <input type="text" name="name" class="form-control" value="<?php echo esc(set_value('name', (isset($email) ? $email->name : '')));?>">
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('Admin.form.department');?></label>
                        <select name="department_id" class="form-control custom-select">
                            <?php
                            $default = (set_value('department_id') ? set_value('department_id') : (isset($email) ? $email->department_id : ''));
                            foreach ($departments as $item) {
                                if($default == $item->id){
                                    echo '<option value="'.$item->id.'" selected>'.$item->name.'</option>';
                                }else{
                                    echo '<option value="'.$item->id.'">'.$item->name.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="tab-pane fade" id="outgoing-content" role="tabpanel" aria-labelledby="outgoing-tab">
                    <div class="form-group">
                        <label><?php echo lang('Admin.form.type');?></label>
                        <select name="outgoing_type" class="form-group custom-select" id="outgoing_type">
                            <?php
                            $default = set_value('outgoing_type', (isset($email) ? $email->outgoing_type : ''));
                            foreach (['php' => 'PHP mail()','smtp' => 'SMTP'] as $k => $v){
                                if($default == $k){
                                    echo '<option value="'.$k.'" selected>'.$v.'</option>';
                                }else{
                                    echo '<option value="'.$k.'">'.$v.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div id="outgoing_details">
                        <div class="form-group">
                            <label><?php echo lang('Admin.settings.host');?></label>
                            <input type="text" name="smtp_host" class="form-control" value="<?php echo set_value('smtp_host', (isset($email) ? $email->smtp_host : 'mail.gmail.com'));?>">
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('Admin.settings.port');?></label>
                            <input type="text" name="smtp_port" class="form-control" value="<?php echo set_value('smtp_port', (isset($email) ? $email->smtp_port : '587'));?>">
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('Admin.settings.encryption');?></label>
                            <select name="smtp_encryption" class="form-group custom-select">
                                <?php
                                $default = set_value('smtp_encryption', (isset($email) ? $email->smtp_encryption : 'tls'));
                                foreach (['' => lang('Admin.form.none'), 'ssl' => 'SSL','tls' => 'TLS'] as $k => $v){
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
                            <label><?php echo lang('Admin.form.username');?></label>
                            <input type="text" name="smtp_username" class="form-control" value="<?php echo set_value('smtp_username', (isset($email) ? $email->smtp_username : ''));?>">
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('Admin.form.password');?></label>
                            <input type="password" name="smtp_password" class="form-control" value="<?php echo esc(set_value('smtp_password', (isset($email) ? $email->smtp_password : '')));?>">
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="incoming-content" role="tabpanel" aria-labelledby="incoming-tab">
                    <div class="form-group">
                        <label><?php echo lang('Admin.form.type');?></label>
                        <select name="incoming_type" class="form-group custom-select" id="incoming_type">
                            <?php
                            $default = set_value('incoming_type', (isset($email) ? $email->incoming_type : ''));
                            foreach (['' => lang('Admin.form.none'),'pipe'=>'Pipe','pop' => 'POP','imap' => 'IMAP'] as $k => $v){
                                if($default == $k){
                                    echo '<option value="'.$k.'" selected>'.$v.'</option>';
                                }else{
                                    echo '<option value="'.$k.'">'.$v.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div id="incoming_details">
                        <div class="form-group">
                            <div class="alert alert-warning">
                                <?php echo lang('Admin.settings.incomingInformation');?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('Admin.settings.host');?></label>
                            <input type="text" name="imap_host" class="form-control" value="<?php echo set_value('imap_host', (isset($email) ? $email->imap_host : ''));?>">
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('Admin.settings.port');?></label>
                            <input type="text" name="imap_port" class="form-control" value="<?php echo set_value('imap_port', (isset($email) ? $email->imap_port : '993'));?>">
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('Admin.form.username');?></label>
                            <input type="text" name="imap_username" class="form-control" value="<?php echo esc(set_value('imap_username', (isset($email) ? $email->imap_username : '')));?>">
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('Admin.form.password');?></label>
                            <input type="password" name="imap_password" class="form-control" value="<?php echo esc(set_value('imap_password', (isset($email) ? $email->imap_password : '')));?>">
                        </div>

                    </div>
                </div>
            </div>


            <div class="form-group">
                <button class="btn btn-primary"><?php echo lang('Admin.form.save');?></button>
                <a href="<?php echo site_url(route_to('staff_emails'));?>" class="btn btn-secondary"><?php echo lang('Admin.form.goBack');?></a>
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
<script type="text/javascript">
    $(function () {
        outgoing_type();
        $("#outgoing_type").on('change', function () {
            outgoing_type();
        });
        incoming_type();
        $("#incoming_type").on('change', function () {
            incoming_type();
        });
    });

</script>
<?php
$this->endSection();
