<?php
/**
 * @var $this \CodeIgniter\View\View
 * @var $validation \CodeIgniter\Validation\Validation
 */
$this->extend('client/template');
$this->section('window_title');
echo lang('Client.submitTicket.menu');
$this->endSection();
$this->section('content');
?>
    <div class="container mt-5">
        <h1 class="heading mb-5">
            <?php echo lang('Client.submitTicket.title');?>
        </h1>

        <?php
        if(isset($error_msg)){
            echo '<div class="alert alert-danger">'.$error_msg.'</div>';
        }

        echo form_open_multipart('',[],[
            'do' => 'submit'
        ]);
        ?>
        <div class="row">
            <div class="col-lg-8">
                <h3 class="mb-3" style="font-weight: 300"><?php echo lang('Client.submitTicket.generalInformation');?></h3>
                <?php if(!client_online()):?>
                    <div class="form-group">
                        <label class="<?php echo ($validation->hasError('fullname') ? 'text-danger' : '');?>">
                            <?php echo lang('Client.form.fullName');?> <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="fullname" value="<?php echo set_value('fullname');?>" class="form-control <?php echo ($validation->hasError('fullname') ? 'is-invalid' : '');?>" required>
                    </div>
                    <div class="form-group">
                        <label class="<?php echo ($validation->hasError('email') ? 'text-danger' : '');?>">
                            <?php echo lang('Client.form.email');?> <span class="text-danger">*</span>
                        </label>
                        <input type="email" name="email" value="<?php echo set_value('email');?>" class="form-control <?php echo ($validation->hasError('email') ? 'is-invalid' : '');?>" required>
                    </div>
                <?php endif;?>
                <div class="form-group">
                    <label>
                        <?php echo lang('Client.form.department');?>
                    </label>
                    <input type="text" value="<?php echo $department->name;?>" class="form-control" readonly>
                </div>
                <?php
                if(isset($customFields)){
                    foreach ($customFields as $customField){
                        echo parseCustomFieldsForm($customField);
                    }
                }
                ?>
                <h3 class="mt-5 mb-3" style="font-weight: 300"><?php echo lang('Client.form.yourMessage');?></h3>
                <div class="form-group">
                    <label class="<?php echo ($validation->hasError('subject') ? 'text-danger' : '');?>">
                        <?php echo lang('Client.form.subject');?> <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="subject" value="<?php echo set_value('subject');?>" class="form-control <?php echo ($validation->hasError('subject') ? 'is-invalid' : '');?>" required>
                </div>

                <div class="form-group">
                    <textarea name="message" rows="15" class="form-control <?php echo ($validation->hasError('message') ? 'is-invalid' : '');?>" required><?php echo set_value('message');?></textarea>
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
                        <small class="text-muted"><?php echo lang('Client.form.allowedFiles');?> <?php echo '*.'.implode(', *.', unserialize(site_config('ticket_file_type')));?></small>
                    </div>
                    <?php
                }
                if(isset($captcha)){
                    echo $captcha;
                }
                ?>
                <div class="form-group">
                    <button class="btn btn-primary"><?php echo lang('Client.form.submit');?></button>
                </div>
            </div>
        </div>

        <?php
        echo form_close();
        ?>
    </div>



<?php
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