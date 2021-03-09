<?php
/**
 * @var $this \CodeIgniter\View\View
 */
?><!DOCTYPE html>
<html class="no-js h-100" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>HelpDeskZ Control Panel</title>
    <meta name="description" content="A high-quality &amp; free Bootstrap admin dashboard template pack that comes with lots of templates and components.">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php
    echo link_tag('assets/helpdeskz/images/favicon.ico','icon','image/x-icon').
        link_tag('assets/components/fontawesome/css/all.min.css').
        link_tag('assets/components/bootstrap/css/bootstrap.min.css').
        link_tag('assets/admin/styles/shards-dashboards.1.1.0.css').
        link_tag('assets/components/daterangepicker/daterangepicker.css').
        link_tag('assets/components/sweetalert/sweetalert2.min.css').
        link_tag('assets/helpdeskz/css/staff.css');
    ?>
</head>


<body class="h-100">

<div class="container-fluid">
    <div class="row">
        <!-- Main Sidebar -->
        <aside class="main-sidebar col-12 col-md-3 col-lg-2 px-0">
            <div class="main-navbar">
                <nav class="navbar align-items-stretch navbar-light bg-white flex-md-nowrap border-bottom p-0">
                    <a class="navbar-brand w-100 mr-0" href="<?php echo site_url(route_to('staff_tickets'));?>" style="line-height: 25px;">
                        <div class="d-table m-auto">
                            <img id="main-logo" class="d-inline-block align-top mr-1" style="max-width: 150px;" src="<?php echo base_url('assets/helpdeskz/images/logo.png');?>">
                        </div>
                    </a>
                    <a class="toggle-sidebar d-sm-inline d-md-none d-lg-none">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                </nav>
            </div>
            <?php
            echo form_open(site_url(route_to('staff_tickets_search')), [
                'method' => 'get',
                'class' => 'main-sidebar__search w-100 border-right d-sm-flex d-md-none d-lg-none'
            ]);
            ?>
                <div class="input-group input-group-seamless ml-3">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                    <input name="keyword" class="navbar-search form-control" type="text" placeholder="<?php echo lang('Admin.form.searchTicket');?>..." aria-label="Search"> </div>
            <?php
            echo form_close();
            ?>
            <div class="nav-wrapper">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?php echo (uri_page() == 'tickets' ? 'active' : '');?>" href="<?php echo site_url(route_to('staff_tickets'));?>">
                            <i class="fas fa-headset"></i>
                            <span><?php echo lang('Admin.tickets.menu');?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (uri_page() == 'canned-responses' ? 'active' : '');?>" href="<?php echo site_url(route_to('staff_canned'));?>">
                            <i class="far fa-comment-dots"></i>
                            <span><?php echo lang('Admin.cannedResponses.menu');?></span>
                        </a>
                    </li>

                    <li class="nav-item dropdown <?php echo (uri_page() == 'kb' ? 'show' : '');?>">
                        <a class="nav-link dropdown-toggle <?php echo (uri_page() == 'kb' ? 'active' : '');?>" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
                            <i class="fa fa-book"></i>
                            <span><?php echo lang('Admin.kb.menu');?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-small <?php echo (uri_page() == 'kb' ? 'show' : '');?>">
                            <a class="dropdown-item <?php echo strpos(uri_string(), 'kb/categories') !== false ? 'active' : '';?>" href="<?php echo site_url(route_to('staff_kb_categories'));?>"><?php echo lang('Admin.kb.categories');?></a>
                            <a class="dropdown-item <?php echo strpos(uri_string(), 'kb/articles') !== false ? 'active' : '';?>" href="<?php echo site_url(route_to('staff_kb_articles'));?>"><?php echo lang('Admin.kb.articles');?></a>
                        </div>
                    </li>
                    <?php
                    if(staff_data('admin') == 1){
                        ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (uri_page() == 'departments' ? 'active' : '');?>" href="<?php echo site_url(route_to('staff_departments'));?>">
                                <i class="fas fa-project-diagram"></i>
                                <span><?php echo lang('Admin.tickets.departments');?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (uri_page() == 'agents' ? 'active' : '');?>" href="<?php echo site_url(route_to('staff_agents'));?>">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <span><?php echo lang('Admin.agents.menu');?></span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php echo (uri_page() == 'users' ? 'active' : '');?>" href="<?php echo site_url(route_to('staff_users'));?>">
                                <i class="fas fa-users"></i>
                                <span><?php echo lang('Admin.users.menu');?></span>
                            </a>
                        </li>

                        <li class="nav-item dropdown <?php echo (uri_page() == 'tools' ? 'show' : '');?>">
                            <a class="nav-link dropdown-toggle <?php echo (uri_page() == 'tools' ? 'active' : '');?>" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
                                <i class="fa fa-tools"></i>
                                <span><?php echo lang('Admin.tools.menu');?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-small <?php echo (uri_page() == 'tools' ? 'show' : '');?>">
                                <a class="dropdown-item <?php echo strpos(uri_string(), 'tools/custom-fields') !== false ? 'active' : '';?>" href="<?php echo site_url(route_to('staff_custom_fields'));?>"><?php echo lang('Admin.tools.customFields');?></a>
                            </div>
                        </li>
                        <li class="nav-item dropdown <?php echo (uri_page() == 'setup' ? 'show' : '');?>">
                            <a class="nav-link dropdown-toggle <?php echo (uri_page() == 'setup' ? 'active' : '');?>" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
                                <i class="fa fa-cogs"></i>
                                <span><?php echo lang('Admin.settings.menu');?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-small <?php echo (uri_page() == 'setup' ? 'show' : '');?>">
                                <a class="dropdown-item <?php echo strpos(uri_string(), 'setup/general') !== false ? 'active' : '';?>" href="<?php echo site_url(route_to('staff_general_settings'));?>"><?php echo lang('Admin.settings.general');?></a>
                                <a class="dropdown-item <?php echo strpos(uri_string(), 'setup/security') !== false ? 'active' : '';?>" href="<?php echo site_url(route_to('staff_security_settings'));?>"><?php echo lang('Admin.settings.security');?></a>
                                <a class="dropdown-item <?php echo strpos(uri_string(), 'setup/tickets') !== false ? 'active' : '';?>" href="<?php echo site_url(route_to('staff_tickets_settings'));?>"><?php echo lang('Admin.settings.tickets');?></a>
                                <a class="dropdown-item <?php echo strpos(uri_string(), 'setup/kb') !== false ? 'active' : '';?>" href="<?php echo site_url(route_to('staff_kb_settings'));?>"><?php echo lang('Admin.settings.kb');?></a>
                                <a class="dropdown-item <?php echo strpos(uri_string(), 'setup/email-templates') !== false ? 'active' : '';?>" href="<?php echo site_url(route_to('staff_email_templates'));?>"><?php echo lang('Admin.settings.emailTemplates');?></a>
                                <a class="dropdown-item <?php echo strpos(uri_string(), 'setup/email-addresses') !== false ? 'active' : '';?>" href="<?php echo site_url(route_to('staff_emails'));?>"><?php echo lang('Admin.settings.emailAddresses');?></a>
                                <a class="dropdown-item <?php echo strpos(uri_string(), 'setup/api') !== false ? 'active' : '';?>" href="<?php echo site_url(route_to('staff_api'));?>"><?php echo lang('Api.configuration');?></a>
                            </div>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </aside>
        <!-- End Main Sidebar -->
        <main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
            <div class="main-navbar sticky-top bg-white">
                <!-- Main Navbar -->
                <nav class="navbar align-items-stretch navbar-light flex-md-nowrap p-0">
                    <?php
                    echo form_open(site_url(route_to('staff_tickets_search')), [
                        'method' => 'get',
                        'class' => 'main-navbar__search w-100 d-none d-md-flex d-lg-flex'
                    ]);
                    ?>
                        <div class="input-group input-group-seamless ml-3">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </div>
                            </div>
                            <input class="navbar-search form-control" name="keyword" type="text" placeholder="<?php echo lang('Admin.form.searchTicket');?>..." aria-label="Search"> </div>
                    <?php
                    echo form_close();
                    ?>
                    <ul class="navbar-nav border-left flex-row ">
                        <li class="nav-item border-right">
                            <div class="text-nowrap px-3 pt-3">
                                <a href="<?php echo site_url(route_to('staff_ticket_new'));?>" class="btn btn-light btn-sm">
                                    <i class="fa fa-edit"></i> <?php echo lang('Admin.tickets.newTicket');?>
                                </a>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-nowrap px-3" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                <img class="user-avatar rounded-circle mr-2" src="<?php echo staff_avatar(staff_data('avatar'));?>" alt="User Avatar">
                                <span class="d-none d-md-inline-block"><?php echo staff_data('fullname');?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-small">
                                <a class="dropdown-item" href="<?php echo site_url(route_to('staff_profile'));?>">
                                    <i class="fas fa-user-cog"></i> <?php echo lang('Admin.account.profile');?></a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="<?php echo site_url(route_to('staff_logout'));?>">
                                    <i class="fas fa-sign-out-alt text-danger"></i> <?php echo lang('Admin.account.logout');?>
                                </a>

                            </div>
                        </li>
                    </ul>
                    <nav class="nav">
                        <a href="#" class="nav-link nav-link-icon toggle-sidebar d-md-inline d-lg-none text-center border-left" data-toggle="collapse" data-target=".header-navbar" aria-expanded="false" aria-controls="header-navbar">
                            <i class="fa fa-bars"></i>
                        </a>
                    </nav>
                </nav>
            </div>
            <!-- / .main-navbar -->
            <div class="main-content-container container-fluid px-4">
                <?php
                $this->renderSection('content');
                ?>
            </div>
            <footer class="main-footer d-flex mt-3 p-2 px-3 bg-white border-top">
                <span class="copyright ml-auto my-auto mr-2">Copyright © 2015 - <?php echo date('Y');?>
              <a href="https://helpdeskz.com" rel="nofollow">HelpDeskZ v<?php echo HDZ_VERSION;?></a>
            </span>
            </footer>
        </main>
    </div>
</div>



<!-- Required Js -->
<script>
    var langRemoveConfirmation = '<?php echo addcslashes(lang('Admin.tickets.removeConfirmation'),"'");?>';
    var langRemoveCannedConfirmation = '<?php echo addcslashes(lang('Admin.cannedResponses.removeConfirmation'),"'");?>';
    var langKbCatConfirmation = '<?php echo addcslashes(lang('Admin.kb.removeConfirmation'), "'");?>';
    var langKbArticleConfirmation = '<?php echo addcslashes(lang('Admin.kb.removeArticleConfirmation'), "'");?>';
    var langDepartmentConfirmation = '<?php echo addcslashes(lang('Admin.tickets.departmentsRemoveConfirmation'), "'");?>';
    var langAgentsConfirmation = '<?php echo addcslashes(lang('Admin.agents.removeConfirmation'), "'");?>';
    var langEmailConfirmation = '<?php echo addcslashes(lang('Admin.settings.emailDeletionConfirm'), "'");?>';
    var langCustomFieldConfirmation = '<?php echo addcslashes(lang('Admin.tools.customFieldConfirm'), "'");?>';
    var langUserConfirmation = '<?php echo addcslashes(lang('Admin.users.removeUserConfirmation'), "'");?>';
    var langNoteConfirmation = '<?php echo addcslashes(lang('Admin.tickets.notesRemoveConfirmation'), "'");?>';

    var langDelete = '<?php echo addcslashes(lang('Admin.form.delete'),"'");?>';
    var langCancel = '<?php echo addcslashes(lang('Admin.form.cancel'),"'");?>';
</script>
<?php
echo script_tag('assets/components/jquery/jquery.min.js').
    script_tag('assets/components/bootstrap/js/bootstrap.bundle.min.js').
    script_tag('assets/admin/scripts/shards.min.js').
    script_tag('assets/admin/scripts/shards-dashboards.1.1.0.js').
    script_tag('assets/components/daterangepicker/moment.min.js').
    script_tag('assets/components/daterangepicker/daterangepicker.js').
    script_tag('assets/components/sweetalert/sweetalert2.all.min.js').
    script_tag('assets/components/blockui/jquery.blockUI.js').
    script_tag('assets/components/bs-custom-file-input/bs-custom-file-input-min.js').
    script_tag('assets/helpdeskz/js/staff.js');
$this->renderSection('script_block');
?>
</body>

</html>
