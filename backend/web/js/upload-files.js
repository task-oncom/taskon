$(document).ready(function() { 
    
    $('body').on('click', "#course-form .kv-file-remove", function(){
        $('#courses-filename').val('');
    });

    /* Вызов формы редактирования данных документа */
    $('body').on('click', '.upd-doc-projs', function(){
       link = jQuery(this);
       doc = link.data('id');
       jQuery.ajax({
           type: 'POST',
           url: "/school/lessons-admin/update-form-document",
           data: {'docId': doc},
           success: function(data){  
              jQuery('#doc-upd').append(
                    data      
              );
           }
        });
    });
    /* Вызов формы создания документа */
    jQuery('#add-doc-projs').click(function() {
          jQuery('#doc-items').append(
              '<div class="tag-item">'+
                 '<input type="file" value="" name="LessonImage[]" id="LessonImage">'+
                '<a title="Удалить документ" href="javascript:void(0);" class="remove-doc">\n\
                  X</a>'+
                '<label for="Doc_name">Введите имя документа<span class="required"> *</span></label>'+
                '<input class="span12" name="Filename[]" type="text" id="Doc_name">'+
              '</div>'     
          );
    });

    /* Удаление документа */
    jQuery('body').on('click', '.remove-doc-projs', function(){
        link = jQuery(this);
        doc = link.attr('value');
        jQuery.ajax({
            type: 'POST',
            url: '/school/lessons-admin/delete-document',
            data: {'docId': doc},
            success: function(){
                link.parent().remove();
            }
         });
     });
    /* Сохранение изменений в документе и закрытие формы редактирования */
    jQuery('body').on('click', '.btn-doc-proj', function(){
       link = jQuery(this);
       name =  link.parent().children('input#Doc_name').val();
       id = link.data('id');
       block_doc = jQuery('#document-'+id);
       jQuery.ajax({
           type: 'POST',
           url: '/school/lessons-admin/update-document',
           data: {'name': name, 'docId': doc},
           success: function(response){  
              link.parent().remove();
              block_doc.replaceWith(response);
           }
        });
    });
}); 