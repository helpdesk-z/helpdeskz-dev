<?php
/**
 * @var $this \CodeIgniter\View\View
 */
$this->section('window_title');
echo site_config('windows_title');
$this->endSection();
$this->extend('client/template');
$this->section('content');
?>
    <div class="slider-block">
        <div class="slider-content">
            <div class="container">
                <?php echo form_open(route_to('search'),['method'=>'get']);?>
                <h1><?php echo lang('Client.kb.howCanWeHelpYou');?></h1>
                <div class="row justify-content-center">
                    <div class="col-lg-7">
                        <div class="input-group input-group-lg">
                            <input type="text" name="keyword" value="<?php echo set_value('keyword');?>" placeholder="<?php echo lang('Client.kb.search');?>" class="form-control">
                            <div class="input-group-append">
                                <button class="btn btn-primary btn"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <?php if($category_id == 0):?>
            <h1 class="heading mb-4"><?php echo lang('Client.kb.title');?></h1>
        <?php else:?>
            <div class="mb-5">
                <a class="inactive_link" href="<?php echo site_url();?>"><?php echo lang('Client.kb.title');?> &nbsp; /</a>
                <?php
                if($parents = kb_parents($category->parent)){
                    foreach ($parents as $item){
                        echo ' &nbsp; <a class="inactive_link" href="'.site_url(route_to('category', $item->id, url_title($item->name))).'">'.$item->name.' &nbsp; /</a>';
                    }
                }
                echo ' &nbsp; <a class="static_link" href="'.site_url(route_to('category', $category->id, url_title($category->name))).'">'.$category->name.'</a>';
                ?>
            </div>
            <h2 class="sub_heading mb-3"><?php echo $category->name?></h2>
        <?php endif;?>
        <div class="row">
            <div class="col-lg-8">
                <?php if($categories = kb_categories($category_id)):?>
                    <div class="row">
                        <?php foreach ($categories as $item):?>
                            <?php $total_articles = kb_count_articles($item->id);?>
                            <?php if($total_articles > 0):?>
                                <div class="col-lg-6 mt-4">
                                    <div class="pt-2">
                                        <a class="kb_category" href="<?php echo site_url(route_to('category', $item->id, url_title($item->name)));?>">
                                            <i class="fa fa-folder-open-o kb_article_icon pr-2"></i> <?php echo $item->name;?>
                                        </a>
                                        <span class="text-muted float-right"><?php echo '('.$total_articles.')';?></span>
                                        <hr>
                                    </div>
                                    <?php foreach (kb_articles_category($item->id) as $article):?>
                                        <div class="py-2">
                                            <i class="fa fa-file-text-o kb_article_icon pr-3"></i>
                                            <a href="<?php echo site_url(route_to('article', $article->id, url_title($article->title)));?>">
                                                <?php echo $article->title;?>
                                            </a>
                                        </div>
                                    <?php endforeach;?>
                                    <?php if($total_articles > site_config('kb_articles')):?>
                                        <div class="py-2">
                                            <a class="static_link" href="<?php echo site_url(route_to('category', $item->id, url_title($item->name)));?>">
                                                &raquo; <?php echo lang('Client.kb.moreTopics');?>
                                            </a>
                                        </div>
                                    <?php endif;?>
                                </div>
                            <?php endif;?>
                        <?php endforeach;?>
                    </div>
                <?php endif;?>

                <!-- Articles -->
                <?php if($articles = kb_articles($category_id)):?>
                    <div class="list-group mt-5">
                        <?php foreach ($articles as $item):?>
                            <div class="list-group-item border-left-0  border-right-0">

                                <div class="float-left">
                                    <div class="float-left mr-3">
                                        <i class="fa fa-file-text-o kb_article_icon_lg"></i>
                                    </div>
                                    <div class="mb-1">
                                        <a class="font-weight-bold" href="<?php echo site_url(route_to('article', $item->id, url_title($item->title)));?>">
                                            <?php echo $item->title;?>
                                        </a>
                                    </div>

                                    <div class="text-muted">
                                        <?php echo resume_content($item->content, site_config('kb_maxchar'));?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        <?php endforeach;?>
                    </div>
                <?php endif;?>
            </div>


            <div class="col">
                <div class="pl-3 pt-3">
                    <?php if($article_list = kb_popular()):?>
                        <div class="mb-5">
                            <h4 class="mb-4"><?php echo lang('Client.kb.mostPopular');?></h4>
                            <?php foreach ($article_list as $item):?>
                                <div class="mb-3">
                                    <i class="fa fa-file-text-o kb_article_icon pr-3"></i>
                                    <a href="<?php echo site_url(route_to('article', $item->id, url_title($item->title)));?>">
                                        <?php echo $item->title;?>
                                    </a>
                                </div>
                            <?php endforeach;?>
                        </div>
                    <?php endif;?>

                    <?php if($article_list = kb_newest()):?>
                        <h4 class="mb-4"><?php echo lang('Client.kb.newest');?></h4>
                        <?php foreach ($article_list as $item):?>
                            <div class="mb-3">
                                <i class="fa fa-file-text-o kb_article_icon pr-3"></i>
                                <a href="<?php echo site_url(route_to('article', $item->id, url_title($item->title)));?>">
                                    <?php echo $item->title;?>
                                </a>
                            </div>
                        <?php endforeach;?>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
<?php
$this->endSection();