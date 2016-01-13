/*   
Template Name: Color Admin - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.2
Version: 1.6.0
Author: Sean Ngu
Website: http://www.seantheme.com/color-admin-v1.6/admin/
*/

var handleDataTableColVis = function() {
	"use strict";
    $('table.table.table-striped.table-bordered .empty').closest('tr').remove();
    if ($('table.table.table-striped.table-bordered').length !== 0) {
        var table = $('table.table.table-striped.table-bordered').DataTable({
            dom: 'C<"clear">lfrtip',
            paging: false,
            searching: true,
            oLanguage: {
                sSearch: 'Поиск:',
                sEmptyTable: 'Ничего не найдено'
            },
            ordering:  false,
            info: false,
            bSortCellsTop: true,
            buttonText: 'Настройка таблицы',
        });
        new $.fn.dataTable.ColVis( table, {
            buttonText: 'Настройка таблицы',
            dom: 'C<"clear">lfrtip',
            paging: false,
            language: {
                sSearch: 'Поиск:'
            },
            searching: true,
            bSortCellsTop: true,
            ordering:  false,
            info: false
        } );
    }
};

var TableManageColVis = function () {
	"use strict";
    return {
        //main function
        init: function () {
            handleDataTableColVis();
        }
    };
}();