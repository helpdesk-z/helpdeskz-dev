<?php
/**
 * @var $this \CodeIgniter\View\View
 */
$this->extend('client/page_template');
$this->section('window_title');
echo $article->title;
$this->endSection();
$this->section('breadcrumb');
?>
    <a class="inactive_link" href="<?php echo site_url();?>"><?php echo lang('Client.kb.title');?> &nbsp; /</a>
<?php
if($article->category > 0){
    if($parents = kb_parents($category->parent)){
        foreach ($parents as $item){
            echo ' &nbsp; <a class="inactive_link" href="'.site_url(route_to('category', $item->id, url_title($item->name))).'">'.$item->name.' &nbsp; /</a>';
        }
    }
    echo ' &nbsp; <a class="static_link" href="'.site_url(route_to('category', $category->id, url_title($category->name))).'">'.$category->name.'</a>';
}
?>
<?php
$this->endSection();
$this->section('page_content');
?>
    <h2 class="sub_heading mb-3"><i class="fa fa-file-text-o kb_article_icon_lg"></i> <?php echo $article->title?></h2>
    <div class="article_description mb-5">
        <?php echo lang_replace('Client.kb.postedOn', ['%date%' => dateFormat($article->date)]);?>
        <hr>
    </div>
    <div><?php echo $article->content;?></div>

<?php if($attachments = article_files($article->id)):?>
    <div class="knowledgebasearticleattachment"><?php echo lang('Client.form.attachments');?></div>
    <?php foreach ($attachments as $item):?>
        <div>
            <span class="knowledgebaseattachmenticon"></span>
            <a href="<?php echo site_url(route_to('article', $article->id, url_title($article->title)).'?download='.$item->id);?>" target="_blank"><?php echo $item->name;?> (<?php echo $item->filesize;?>)</a>
        </div>
    <?php endforeach;?>
<?php endif;?>
<?php
$this->endSection();
