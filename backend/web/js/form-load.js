$(function() {
    $('.datepicker-autoClose').datepicker({
        dateFormat: 'dd.mm.yy',
        todayHighlight: true,
        regional: 'ru',
        autoclose: true,
        todayBtn: true
    });

    $('.datetimepicker-autoClose').datetimepicker({
        todayHighlight: true,
        language: 'ru',
        autoclose: true,
        todayBtn: true,
        format: 'dd.mm.yyyy hh:ii'
    });

    FormSliderSwitcher.init();

    tinymce.init({
        selector: "textarea",theme: "modern",
        language: "ru",
        custom_elements: "emstart,emend,header,main,span",
        extended_valid_elements: "span[id|name|class|style],i[id|name|class|style],ul[id|name|class|style],li[id|name|class|style]",
        height: '350px',
        menubar: "edit insert view format table tools",
        plugins: [
            "advlist autolink link image code lists charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
            "table contextmenu directionality emoticons paste textcolor responsivefilemanager codesample"
        ],
        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | codesample | styleselect",
        toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
        image_advtab: true ,
        forced_root_block : false,
        force_br_newlines : false,
        force_p_newlines : true,
        external_filemanager_path:"/filemanager/",
        filemanager_title:"Responsive Filemanager" ,
        external_plugins: { "filemanager" : "/filemanager/plugin.min.js"}
    });
});
