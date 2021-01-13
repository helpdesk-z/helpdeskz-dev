<?php

echo script_tag('https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.0.4/tinymce.min.js').
    script_tag('https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.0.4/plugins/spellchecker/plugin.min.js');
?>
<script>
    function HDZImageManager(callback, value, meta) {
        var width = window.innerWidth-20;
        var height = window.innerHeight-20;
        if (width > 1024)  { width = 1024; }
        if (height > 768) { height = 768; }

        //tinyMCE.activeEditor.focus(true);

        var fileUrl = tinyMCE.activeEditor.settings.pthManager+'?';

        // VERSION 5
        tinyMCE.activeEditor.windowManager.openUrl({
            title: "HelpDeskZ Image Manager",
            url: fileUrl,
            width: width,
            height: height,
            inline: 1,
            resizable: true,
            maximizable: true,
            onMessage: function (api, data) {
                if (data.mceAction === 'customAction') {
                    callback(data.url);
                    api.close();
                }
            }

        });
    }


    tinymce.init({
        selector: '#messageBox',
        pthManager: '<?php echo site_url(route_to('staff_editor_uploader'));?>',
        branding: false,
        menubar: false,
        height:300,
        relative_urls: false,
        remove_script_host : false,
        convert_urls : true,
        plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount spellchecker imagetools textpattern help code',
        external_plugins: {
            'HDZImageManager': '<?php echo base_url('assets/components/tinymce-img-uploader/js/plugin.js');?>'
        },
        toolbar: 'formatselect | bold italic strikethrough forecolor backcolor permanentpen formatpainter | link image | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment blockquote code',
        file_picker_callback: function (callback, value, meta) {
            HDZImageManager(callback, value, meta);
        },
    });
</script>