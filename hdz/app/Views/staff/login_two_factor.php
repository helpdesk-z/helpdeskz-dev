<!DOCTYPE html>
<html lang="en">

<head>

    <title>HelpDeskZ Staff Panel</title>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Favicon icon -->
    <?php
    echo link_tag('assets/helpdeskz/images/favicon.ico', 'icon', 'image/x-icon').
        link_tag('assets/components/fontawesome/css/all.min.css').
        link_tag('assets/components/bootstrap/css/bootstrap.min.css').
        link_tag('assets/admin/styles/shards-dashboards.1.1.0.css');
    ?>
</head>
<body>
<!-- [ auth-signin ] start -->
<div class="container pt-5">
    <div class="card">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="card-body">
                    <img src="/assets/helpdeskz/images/logo.png" alt="" class="img-fluid mb-4">
                    <h4 class="mb-3 f-w-400"><?php echo lang('Admin.twoFactor.title');?></h4>
                    <?php
                    if(isset($error_msg)){
                        echo '<div class="alert alert-danger">'.$error_msg.'</div>';
                    }
                    echo form_open('',[],[
                        'do'=>'login',
                        'username' => set_value('username', '', false),
                        'password' => set_value('password', '', false),
                        'remember' => set_value('remember', '')
                    ]);
                    ?>
                    <p></p>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            </div>
                            <input type="text" name="two_factor" class="form-control" placeholder="<?php echo lang('Admin.twoFactor.verificationCode');?>" autocomplete="off">
                        </div>
                        <small class="form-text text-muted"><?php echo lang('Admin.twoFactor.enter2FA');?></small>
                    </div>
                    <button class="btn btn-primary"><?php echo lang('Admin.login.button');?></button>
                    <a href="<?php echo site_url(route_to('staff_login'));?>" class="btn btn-danger"><?php echo lang('Admin.form.cancel');?></a>
                    <?php
                    echo form_close();
                    ?>
                </div>
            </div>
            <div class="col-md-6 d-none d-md-block">
                <img src="<?php echo base_url('assets/helpdeskz/images/desk.jpg');?>" alt="" class="img-fluid">
            </div>
        </div>
    </div>
</div>
<!-- [ auth-signin ] end -->

<!-- Required Js -->
    <?php
    echo script_tag('assets/admin/js/vendor-all.min.js').
        script_tag('assets/admin/plugins/bootstrap/js/bootstrap.min.js');
    ?>
</body>
</html>