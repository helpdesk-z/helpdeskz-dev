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
            <span class="text-uppercase page-subtitle"><?php echo lang('Admin.kb.menu');?></span>
            <h3 class="page-title"><?php echo lang('Admin.kb.categories');?></h3>
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
    '<input type="hidden" name="category_id" id="category_id">'.
    form_close();
?>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-5">
                    <h6 class="mb-0"><?php echo lang('Admin.form.manage');?></h6>
                </div>
                <div class="col-sm-7">
                    <a href="<?php echo site_url(route_to('staff_kb_new_category'));?>" class="btn btn-primary btn-sm float-right"><i class="fa fa-plus"></i> <?php echo lang('Admin.kb.newCategory');?></a>
                </div>
            </div>
        </div>
        <table class="table table-hover table-striped">
            <thead>
            <tr>
                <th><?php echo lang('Admin.kb.category');?></th>
                <th><?php echo lang('Admin.kb.articles');?></th>
                <th><?php echo lang('Admin.form.type');?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php
            if(isset($kb_list)){
                $parent = 0;
                $level = 1;
                foreach ($kb_list as $category){
                    ?>
                    <tr>
                        <td>
                            <?php echo $category->name;?>
                        </td>
                        <td>
                            <?php echo kb_count_articles_category($category->id);?>
                        </td>
                        <td><?php echo ($category->public ? lang('Admin.form.public') : lang('Admin.form.private'));?></td>
                        <td class="text-right">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo lang('Admin.form.action');?>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="<?php echo site_url(route_to('staff_kb_edit_category', $category->id));?>"><i class="far fa-edit"></i> <?php echo lang('Admin.kb.editCategory');?></a>
                                    <a class="dropdown-item" href="<?php echo site_url(route_to('staff_kb_new_category')).'?parent='.$category->id;?>"><i class="far fa-folder-open"></i> <?php echo lang('Admin.kb.addSubcategory');?></a>
                                    <a class="dropdown-item" href="<?php echo site_url(route_to('staff_kb_new_article')).'?category='.$category->id;?>"><i class="far fa-file-alt"></i> <?php echo lang('Admin.kb.newArticle');?></a>
                                    <button class="dropdown-item" onclick="removeCategory(<?php echo $category->id;?>);"><i class="far fa-trash-alt"></i> <?php echo lang('Admin.kb.removeCategory');?></button>
                                </div>
                            </div>
                            <?php
                            if($move_button = kb_cat_move_link($category->id, $category->parent)){
                                echo '<div class="btn-group float-left ml-2">'.$move_button.'</div>';
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
            }else{
                ?>
                <tr>
                    <td colspan="4"><?php echo lang('Admin.error.recordsNotFound');?></td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </div>

<?php
$this->endSection();
