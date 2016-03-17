// Метод добавляющий новое условие
$(document).on('click', 'a.add_condition', function() {
    var id = $(this).data('id');
    var container = $(this).closest('div.btn-group').parent().children('div.conditions-block');
    var key = $(this).closest('tr').data('key');

    $.ajax({
        url: '/triggers/trigger-admin/getconditionhtml',
        data: {
            id: id,
            key: key
        },
        method: 'GET',
        success: function(response) {
            var result = JSON.parse(response);
            container.append(result);
        }
    })
});

// Метод добавляющий новую строку "И"
$(document).on('click', 'button.add-and-condition', function() {
    var container = $(this).closest('table').children('tbody');

    $.ajax({
        url: '/triggers/trigger-admin/getandconditionhtml',
        success: function(response) {
            var result = JSON.parse(response);
            container.append(result);
        }
    })
});

$(document).on('click', '.delete_row', function() {
    $(this).closest('tr').remove();
});
