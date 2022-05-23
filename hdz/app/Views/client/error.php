<?php
/**
 * @var $this \CodeIgniter\View\View
 */
$this->extend('client/page_template');
$this->section('window_title');
echo $title;
$this->endSection();
$this->section('page_content');
?>
<div class="container">
    <h1 class="heading mb-5">
        <?php echo $title;?>
    </h1>
    <hr>
    <div class="my-5">
        <p><?php echo $body;?></p>
    </div>
</div>
<?php
$this->endSection();