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
            <h3 class="page-title"><?php echo isset($article) ? lang('Admin.kb.editArticle') : lang('Admin.kb.newArticle');?></h3>
        </div>
    </div>
    <!-- End Page Header -->



    <div class="card">
        <div class="card-body">
            <?php
            if(isset($error_msg)){
                echo '<div class="alert alert-danger">'.$error_msg.'</div>';
            }
            if(isset($success_msg)){
                echo '<div class="alert alert-success">'.$success_msg.'</div>';
            }

            echo form_open('',['id'=>'manageForm'],['do'=>'submit']);
            ?>
            <div class="form-group">
                <label><?php echo lang('Admin.form.title');?></label>
                <input type="text" class="form-control" name="title" value="<?php echo set_value('title') ? set_value('title') : (isset($article) ? $article->title : '');?>">
            </div>
            <div class="form-group">
                <label><?php echo lang('Admin.kb.category');?></label>
                <select name="category_id" class="form-control custom-select">
                    <?php
                    $default = set_value('category_id') ? set_value('category_id') : (isset($article) ? $article->category : $category_id);
                    if(isset($kb_list)){
                        $parent = 0;
                        foreach ($kb_list as $item){
                            if(isset($category) && $category->id == $item->id){
                                continue;
                            }
                            if($default == $item->id){
                                echo '<option value="'.$item->id.'" selected>'.$item->name.'</option>';
                            }else{
                                echo '<option value="'.$item->id.'">'.$item->name.'</option>';
                            }
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label><?php echo lang('Admin.form.type');?></label>
                <select name="public" class="form-control custom-select">
                    <?php
                    $default = set_value('public') ? set_value('public') : (isset($article) ? $article->public : 1);
                    foreach (['1' => lang('Admin.form.public'),'0'=>lang('Admin.form.private')] as $k => $v) {
                        if($default == $k){
                            echo '<option value="'.$k.'" selected>'.$v.'</option>';
                        }else{
                            echo '<option value="'.$k.'">'.$v.'</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <textarea name="content" id="messageBox" class="form-control"><?php echo set_value('content') ? set_value('content') : (isset($article) ? $article->content : '');?></textarea>
            </div>
            <div class="form-group">
                <button class="btn btn-primary"><?php echo lang('Admin.form.submit');?></button>
                <a href="<?php echo site_url(route_to('staff_kb_articles'));?>" class="btn btn-secondary"><?php echo lang('Admin.form.goBack');?></a>
            </div>
            <?php
            echo form_close();
            ?>
        </div>
    </div>

<?php
$this->endSection();
$this->section('script_block');
include __DIR__.'/tinymce.php';
$this->endSection();