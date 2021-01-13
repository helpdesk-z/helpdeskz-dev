<?php
/**
 * @var $this \CodeIgniter\View\View
 * @var $pager \CodeIgniter\Pager\Pager
 */
$this->extend('staff/template');
$this->section('content');
?>
    <!-- Page Header -->
    <div class="page-header row no-gutters py-4">
        <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
            <span class="text-uppercase page-subtitle"><?php echo lang('Admin.kb.menu');?></span>
            <h3 class="page-title"><?php echo lang('Admin.kb.articles');?></h3>
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
echo form_open('',['id'=>'manageForm'],['do'=>'remove']).
    '<input type="hidden" name="article_id" id="article_id">'.
    form_close();
?>
    <div class="card mb-3">
        <div class="card-header border-bottom">
            <div class="row">
                <div class="col-sm-5">
                    <h6 class="mb-0"><?php echo lang('Admin.form.manage');?></h6>
                </div>
                <div class="col-sm-7">
                    <a href="<?php echo site_url(route_to('staff_kb_new_article'));?>" class="btn btn-primary btn-sm float-right"><i class="fa fa-plus"></i> <?php echo lang('Admin.kb.newArticle');?></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label><?php echo lang('Admin.form.view');?></label>
                <select name="parent" class="form-control custom-select" onchange="if(this.value === ''){ location.href= '<?php echo site_url(route_to('staff_kb_articles'));?>'; }else{location.href='<?php echo site_url(route_to('staff_kb_articles'));?>/category/'+this.value;}">
                    <option value=""><?php echo lang('Admin.form.all');?></option>
                    <?php
                    if(isset($kb_list)){
                        $parent = 0;
                        foreach ($kb_list as $item){
                            $total_articles = kb_count_articles_category($item->id, false);
                            if($category == $item->id){
                                echo '<option value="'.$item->id.'" selected>'.$item->name.' ('.$total_articles.')</option>';
                            }else{
                                echo '<option value="'.$item->id.'">'.$item->name.' ('.$total_articles.')</option>';
                            }
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <table class="table table-hover table-striped">
            <thead>
            <tr>
                <th><?php echo lang('Admin.kb.article');?></th>
                <th><?php echo lang('Admin.kb.author');?></th>
                <th><?php echo lang('Admin.form.dateCreated');?></th>
                <th><?php echo lang('Admin.form.lastUpdate');?></th>
                <th><?php echo lang('Admin.form.views');?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php
            if(count($articles_result) == 0){
                ?>
                <tr>
                    <td colspan="6"><?php echo lang('Admin.error.recordsNotFound');?></td>
                </tr>
            <?php
            }else{
                foreach ($articles_result as $item){
                    ?>
                    <tr>
                        <td>
                            <?php echo resume_content($item->title, 50);?><br>
                            <small><i class="fa fa-folder-open-o"></i> <?php echo $item->category_name;?></small>
                        </td>
                        <td><?php echo $item->author;?></td>
                        <td><?php echo dateFormat($item->date);?></td>
                        <td><?php echo ($item->last_update == 0 ? '-' : dateFormat($item->last_update));?></td>
                        <td><?php echo $item->views;?></td>
                        <td class="text-right">
                            <div class="dropdown ">
                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo lang('Admin.form.action');?>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="<?php echo site_url(route_to('staff_kb_edit_article', $item->id));?>"><i class="far fa-edit"></i> <?php echo lang('Admin.kb.editArticle');?></a>
                                    <button class="dropdown-item" onclick="removeArticle(<?php echo $item->id;?>);"><i class="far fa-trash-alt"></i> <?php echo lang('Admin.form.delete');?></button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
            </tbody>
        </table>
    </div>
<?php
echo $pager->links();

$this->endSection();
