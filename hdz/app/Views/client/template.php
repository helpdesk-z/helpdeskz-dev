<?php
/**
 * @var $this \CodeIgniter\View\View
 */
$page_controller = isset($page_controller) ? $page_controller : '';
?><!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSS -->
    <?php
    echo link_tag('favicon.ico','icon','image/x-icon').
        link_tag('https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,400i,600').
        link_tag('assets/components/font-awesome/css/font-awesome.min.css').
        link_tag('assets/components/bootstrap/css/bootstrap.min.css').
        link_tag('assets/components/select2/css/select2.min.css').
        link_tag('assets/components/select2/css/select2-bootstrap.min.css').
        link_tag('assets/helpdeskz/css/helpdesk.css');
    $this->renderSection('css_block');
    ?>
    <title><?php $this->renderSection('window_title');?></title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="<?php echo site_url();?>"><img src="<?php echo site_logo();?>" style="max-height: 50px" class="img-fluid"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo site_url(route_to('home'));?>"><?php echo lang('Client.kb.menu');?></a>
                </li>
                <?php if(client_online()):?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo site_url(route_to('view_tickets'));?>"><?php echo lang('Client.viewTickets.menu');?></a>
                    </li>
                <?php endif;?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo site_url(route_to('submit_ticket'));?>"><?php echo lang('Client.submitTicket.menu');?></a>
                </li>
                <?php if(client_online()):?>
                    <li class="nav-item dropdown <?php if($page_controller == 'account'){ echo 'active';}?>">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo lang('Client.account.menu');?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="<?php echo site_url(route_to('profile'));?>"><?php echo lang('Client.account.editProfile');?></a>
                            <a class="dropdown-item" href="<?php echo site_url(route_to('logout'));?>"><?php echo lang('Client.account.logout');?></a>
                        </div>
                    </li>
                <?php else:?>
                    <li class="nav-item <?php if($page_controller == 'login'){ echo 'active';}?>">
                        <a class="nav-link" href="<?php echo site_url(route_to('login'));?>"><?php echo lang('Client.login.menu');?></a>
                    </li>
                <?php endif;?>
            </ul>
        </div>
    </div>
</nav>
<?php
$this->renderSection('content');
?>


<div id="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6">Copyright &copy; <?php echo site_config('site_name');?></div>
            <div class="col-md-6 text-md-right">
                Powered by <a href="http://www.helpdeskz.com">HelpDeskZ</a>
            </div>
        </div>
    </div>
</div>
<!-- Javascript -->
<?php
echo script_tag('assets/components/jquery/jquery.min.js').
    script_tag('assets/components/bootstrap/js/bootstrap.bundle.min.js').
    script_tag('assets/components/select2/js/select2.min.js').
    script_tag('assets/helpdeskz/js/helpdesk.js');
$this->renderSection('script_block');
?>
</body>
</html>