<?php
/**
 * @var $pager \CodeIgniter\Pager\Pager
 */
?><!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title><?php echo lang('Admin.form.uploadImage');?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    #JS
    echo script_tag('assets/components/tinymce-img-uploader/js/jquery.min.js');
    #CSS
    echo link_tag('assets/components/tinymce-img-uploader/css/lightbox.css').
        link_tag('assets/components/tinymce-img-uploader/css/featherlight.css').
        link_tag('assets/components/tinymce-img-uploader/css/dropzone.css').
        link_tag('assets/components/tinymce-img-uploader/css/styles.css').
        link_tag('assets/components/font-awesome/css/font-awesome.min.css');
    ?>
</head>
<body>
<div class="_mt15 _mr15 _ml15">
    <!-- BUTTONS -->
    <div class="row">
        <a class="action" id="btnToggleUploader"><?php echo lang('Admin.form.uploadImage');?></a>
    </div>
    <!-- UPLOADER -->
    <?php echo csrf_field('tokenInput');?>
    <div class="row">
        <div id="uploader" class="_hide">
            <form action="<?php echo site_url(route_to('staff_editor_uploader'));?>" class="dropzone">
                <input type="hidden" name="do" value="upload">
                <input type="hidden" id="uploadToken" name="c" value="c">
            </form>
        </div>
    </div>


    <div class="row">
        <div class="_mb25">
            <div class="_mb10 _mt10"><?php echo lang('Admin.form.total');?> <span id="Total"><?php echo $total_images ?></span></div>
            <div id="images" class="images">
                <?php
                #---------------------------------------------------
                # IMAGES IN DIR WITH PAGINATION
                #---------------------------------------------------
                $counter 	= 0;
                foreach ($thumb_files as $file) {
                    $counter ++;
                    $filePath = str_replace(array('../','thumbs/'),'',$file);
                    if(!file_exists($filePath)){
                        continue;
                    }
                    $fileName = basename($filePath);
                    $LargeImageURL = str_replace(FCPATH, base_url().'/', $filePath);

                    # check if file exists
                    list($width, $height, $type, $attr) = getimagesize($filePath);

                    $img_url = str_replace(FCPATH, base_url().'/', $file);
                    ?>
                    <div id="IMG<?php echo $counter;?>" class="wrap">
                        <?php
                        echo img($img_url, false, [
                            'class' => 'img-thumbnail',
                            'id' => 'btnInsertFile',
                            'title' => $fileName,
                            'alt' => $fileName,
                            'data-url' => $LargeImageURL,
                            'data-width' => $width,
                            'data-height' => $height
                        ]);
                        ?>
                        <div class="info">
                            <div class="buttons">
                                <a id="btnDelete" class="tooltip" title="<?php echo lang('Admin.form.delete');?>" data-id="IMG<?php echo $counter;?>" data-file="<?php echo $fileName;?>"><span><i class="fa fa-trash-o"></i></span></a>
                                <a class="tooltip" title="<?php echo lang('Admin.form.download');?>" href="<?php echo $LargeImageURL;?>" download><i class="fa fa-download"></i></a>
                                <a rel="lightbox" class="tooltip" href="<?php echo $LargeImageURL;?>" title="<?php echo $fileName;?>" data-title="<?php echo $fileName;?>"><i class="fa fa-eye"></i></a>
                            </div>
                            <div class="name" title="<?php echo $fileName;?>"><?php echo resume_content($fileName,13,'..');?></div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>

    </div>
    <?php
    echo $pagination;
    ?>

</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#uploadToken').attr('name', $('#tokenInput').attr('name'));
        $('#uploadToken').val($('#tokenInput').val());
        var fl = null;
        $.featherlight.autoBind = false;
        Dropzone.autoDiscover = false;
        //--------------------------------------
        // DELETE
        //--------------------------------------
        $(document).on("click","#btnDelete",function(e){
            e.preventDefault();
            var DivID 	= $(this).data("id");
            var FileName 	= $(this).data("file");
            var Total 	= $('#Total').text()-1;		// deduct total count
            var CONTAINER = $('#'+DivID);
            var STRING = 'do=delete&file='+ FileName + "&"+$("#tokenInput").attr('name')+"="+$("#tokenInput").val();
            $.ajax({
                type: "POST",
                url: "<?php echo site_url(route_to('staff_editor_uploader'));?>",
                data: STRING,
                dataType: 'json',
                cache: false,
                success: function(msg){
                    $('#tokenInput').attr('name', msg.token_name);
                    $('#tokenInput').val(msg.token_value);
                    $('#uploadToken').attr('name', msg.token_name);
                    $('#uploadToken').val(msg.token_value);
                    $('#Total').text(Total);
                    CONTAINER.fadeOut('25', function() {$(this).remove();});
                    CONTAINER.animate({
                        height: 1,          // Avoiding sliding to 0px (flash on IE)
                        paddingTop: "hide",
                        paddingBottom: "hide"
                    })
                        // Then hide
                        .animate({display:"hide"},{queue:true});
                }
            });

        });


        //--------------------------------------
        // toggle uploader
        //--------------------------------------
        $(document).on("click","#btnToggleUploader",function(e){
            //$('#uploader').toggleClass('_hide');
            $('#uploader').slideToggle('fast');
        });


        //--------------------------------------
        // send picked img to tinymce editor
        //--------------------------------------
        $(document).on("click","img#btnInsertFile",function(e){

            var url = $(this).data("url");

            // detect if image dialog opened
            var title = $('.tox-dialog__title', window.parent.document).text();
            var ImageDialog = title.search("Insert/Edit Image");

            if (ImageDialog == 0) {						// 0 = image dialog present because insert/edit text found
                window.parent.postMessage({
                    mceAction: 'customAction',
                    url: url
                }, '*');
            }else{
                // insert image in Editor
                if (typeof(parent.tinymce) !== "undefined") {
                    parent.tinymce.activeEditor.insertContent('<img src="'+ url +'" width="'+$(this).data('width')+'"  height="'+$(this).data('height')+'">');
                    parent.tinymce.activeEditor.windowManager.close();
                }
            }
        });


        //--------------------------------------
        // DROPZONE - uploader
        //--------------------------------------


        // DropZone Options
        var dropzoneOptions = {
            dictDefaultMessage: '<div><span class="_bold"><?php echo lang('Admin.form.dropImageHere');?></span>',
            acceptedFiles: "<?php echo $allowed_extensions;  # ".jpeg,.jpg,.png,.gif" ?>",
            paramName: "file",
            maxFilesize: <?php echo $max_upload_size/1000 ?>,
            addRemoveLinks: false,
            init: function () {
                this.on("success", function (file) {
                    if(file.xhr.response !== 'undefined'){
                        xhrResponse = JSON.parse(file.xhr.response)
                        $('#tokenInput').attr('name', xhrResponse.token_name);
                        $('#tokenInput').val(xhrResponse.token_value);
                        $('#uploadToken').attr('name', xhrResponse.token_name);
                        $('#uploadToken').val(xhrResponse.token_value);
                    }
                });
            }
        };
        var myDropzone = new Dropzone(".dropzone", dropzoneOptions);					// manual attach it instead

        // check all files uploaded

        myDropzone.on("queuecomplete", function(file, res) {
            if (myDropzone.files[0].status == Dropzone.SUCCESS ) {
                location.reload();
            }
        });


    });

</script>
<?php
echo script_tag('assets/components/tinymce-img-uploader/js/lightbox.js').
    script_tag('assets/components/tinymce-img-uploader/js/featherlight.js').
    script_tag('assets/components/tinymce-img-uploader/js/dropzone.js');
?>
</body>
</html>