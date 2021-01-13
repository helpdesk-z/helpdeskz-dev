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
            <h3 class="page-title"><?php echo isset($category) ? lang('Admin.kb.editCategory') : lang('Admin.kb.newCategory');?></h3>
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
                <label><?php echo lang('Admin.form.categoryName');?></label>
                <input type="text" class="form-control" name="name" value="<?php echo set_value('name', (isset($category) ? $category->name : ''));?>">
            </div>
            <div class="form-group">
                <label><?php echo lang('Admin.form.parentCategory');?></label>
                <select name="parent" class="form-control custom-select">
                    <option value="0"><?php echo lang('Admin.form.rootCategory')?></option>
                    <?php
                    $default = set_value('parent', (isset($category) ? $category->parent : $parent));
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
                    $default = set_value('public', (isset($category) ? $category->public : 1));
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
                <button class="btn btn-primary"><?php echo lang('Admin.form.submit');?></button>
                <a href="<?php echo site_url(route_to('staff_kb_categories'));?>" class="btn btn-secondary"><?php echo lang('Admin.form.goBack');?></a>
            </div>
            <?php
            echo form_close();
            ?>
        </div>
    </div>
<?php
$this->endSection();