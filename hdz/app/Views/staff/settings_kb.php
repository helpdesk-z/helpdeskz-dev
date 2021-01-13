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
            <h3 class="page-title"><?php echo lang('Admin.settings.kb');?></h3>
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
                <label><?php echo lang('Admin.settings.articlesUnderCategory');?></label>
                <input type="number" step="1" min="1" name="kb_articles" class="form-control" value="<?php echo set_value('kb_articles', site_config('kb_articles'));?>">
            </div>
            <div class="form-group">
                <label><?php echo lang('Admin.settings.charLimitArticlePreview');?></label>
                <input type="number" step="1" min="1" name="kb_maxchar" class="form-control" value="<?php echo set_value('kb_maxchar', site_config('kb_maxchar'));?>">
                <small class="text-muted form-text"><?php echo lang('Admin.settings.charLimitArticlePreviewDescription');?></small>
            </div>
            <div class="form-group">
                <label><?php echo lang('Admin.settings.popularArticles');?></label>
                <input type="number" step="1" min="1" name="kb_popular" class="form-control" value="<?php echo set_value('kb_popular', site_config('kb_popular'));?>">
            </div>
            <div class="form-group">
                <label><?php echo lang('Admin.settings.newestArticles');?></label>
                <input type="number" step="1" min="1" name="kb_latest" class="form-control" value="<?php echo set_value('kb_latest', site_config('kb_latest'));?>">
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