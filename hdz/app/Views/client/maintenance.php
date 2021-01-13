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
    echo link_tag('https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,400i,600').
        link_tag('assets/components/font-awesome/css/font-awesome.min.css').
        link_tag('assets/components/bootstrap/css/bootstrap.min.css').
        link_tag('assets/components/select2/css/select2.min.css').
        link_tag('assets/components/select2/css/select2-bootstrap.min.css').
        link_tag('assets/helpdeskz/css/helpdesk.css');
    ?>
    <title><?php echo site_config('windows_title');?></title>
</head>
<body>


<div class="container pt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <img src="<?php echo site_logo();?>" alt="" class="img-fluid mb-4">
                    <p><?php echo $body;?></p>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">Copyright &copy; <?php echo site_config('site_name');?></div>
                        <div class="col-md-6 text-md-right">
                            Powered by <a href="http://www.helpdeskz.com">HelpDeskZ</a>
                        </div>
                    </div>
                </div>
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
?>
</body>
</html>