(function(jQuery)
{
    jQuery.widget('CmsUI.grid', jQuery.CmsUI.gridBase, {
        _version:0.1,

        // default options
        options:{
            mass_removal:false
        },
        parent:function()
        {
            return jQuery.CmsUI.gridBase.prototype;
        },
        _create:function()
        {
            this.parent()._create.call(this);
        },
        _initHandlers:function()
        {
            this.parent()._initHandlers.call(this);

            var self = this;
            self._initSwitchPageSize();
            self._initFilters();

            if (self.options.mass_removal)
            {
                self._initMassRemoval();
            }
        },
        _initMassRemoval:function()
        {
            var self = this;
            jQuery('#mass_remove_button').click(function()
            {
                var jQuerycheckboxes = jQuery('.object_checkbox:checked', self.element);

                if (jQuerycheckboxes.length > 0)
                {
                    if (!confirm('Вы уверены, что хотите удалить отмеченные элементы?'))
                    {
                        return false;
                    }

                    blockUI();
                    var grid_id = self.element.attr('id');

                    jQuerycheckboxes.each(function()
                    {
                        var action = jQuery(this).closest('tr').find('.delete').attr('href');

                        jQuery.fn.yiiGridView.update(grid_id, {
                            type:'POST',
                            url:action,
                            success:null
                        });
                    });

                    jQuery(document).ajaxStop(function()
                    {
                        unblockUI();
                        jQuery.fn.yiiGridView.update(grid_id);
                        jQuery(this).unbind('ajaxStop');
                    });
                }
                else
                {
                    showMsg('Отметьте элементы!');
                }
            });
        },
        _initSwitchPageSize:function()
        {
            var self = this;
            jQuery('.pager_select', self.element).change(function()
            {
                var params = '/model/' + jQuery(this).attr('model') + '/per_page/' + jQuery(this).val() + '/back_url/' + jQuery("#back_url").val();
                location.href = '/main/mainAdmin/SessionPerPage' + params;
            });

        },
        _initFilters:function()
        {
            var self = this;

            var inputs = jQuery('.filters input, .filters select', self.element), //TODO: what with dropdownlist???
                inputs_count = inputs.length;

            if (inputs_count == 0)
            {
                return false;
            }

            var show_filters = false;
            inputs.each(function()
            {
                if (jQuery(this).val())
                {
                    show_filters = true;
                }
            });

            jQuery('.filters:first', self.element)[show_filters ? 'slideDown' : 'slideUp']();

            jQuery('th', self.element).each(function()
            {
                if (jQuery(this).html() == '&nbsp;')
                {
                    jQuery(this).html("<a href='' class='filters_link'>фильтры</a>");
                }
            });

            jQuery('.filters_link', self.element).click(function()
            {
                jQuery('.filters', self.element).slideToggle();
                return false;
            });
        }
    });
})(jQuery);


