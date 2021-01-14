<?php
/**
 * @var $this \CodeIgniter\View\View
 */
?><!DOCTYPE html>
<html lang="en">
<head>
    <title>HelpDeskZ v.<?php echo HDZ_VERSION;?></title>
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
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <img src="<?php echo base_url('assets/helpdeskz/images/logo.png');?>" alt="" class="img-fluid mb-4">
                    <?php
                    echo $this->renderSection('content');
                    ?>
                </div>
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