<?php
/**
 * @var $this \CodeIgniter\View\View
 */
$this->extend('client/template');
$this->section('content');
?>
    <div class="container mt-5">
        <div class="mb-5">
            <?php
            $this->renderSection('breadcrumb');
            ?>
        </div>

        <div class="row">
            <div class="col-lg-8 mb-5 order-1 order-lg-0">
                <?php
                $this->renderSection('page_content');
                ?>
            </div>

            <div class="col order-0 order-lg-1">
                <div class="pl-3">
                    <div class="mb-5">
                        <?php echo form_open(route_to('search'),['method'=>'get']);?>
                        <h4 class="mb-4"><?php echo lang('Client.form.search');?></h4>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" name="keyword" class="form-control" value="<?php echo set_value('keyword');?>" placeholder="<?php echo lang('Client.kb.search');?>">
                                <div class="input-group-append">
                                    <button class="btn btn-primary"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close();?>
                    </div>

                    <!-- Popular articles -->
                    <?php if($article_list = kb_popular()):?>
                        <div class="mb-5 d-none d-lg-block">
                            <h4 class="mb-4"><?php echo lang('Client.kb.mostPopular');?></h4>
                            <?php foreach ($article_list as $item):?>
                                <div class="mb-3">
                                    <i class="fa fa-file-text-o kb_article_icon pr-3"></i>
                                    <a href="<?php echo site_url(route_to('article', $item->id, url_title($item->title)));?>"><?php echo $item->title;?></a>
                                </div>
                            <?php endforeach;?>
                        </div>
                    <?php endif;?>

                    <?php if($article_list = kb_newest()):?>
                        <div class="mb-5 d-none d-lg-block">
                            <h4 class="mb-4"><?php echo lang('Client.kb.newest');?></h4>
                            <?php foreach ($article_list as $item):?>
                                <div class="mb-3">
                                    <i class="fa fa-file-text-o kb_article_icon pr-3"></i>
                                    <a href="<?php echo site_url(route_to('article', $item->id, url_title($item->title)));?>"><?php echo $item->title;?></a>
                                </div>
                            <?php endforeach;?>
                        </div>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
<?php
$this->endSection();