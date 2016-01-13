jQuery(function()
{
    jQuery('.pager_select').change(function()
    {
        var params = '/model/' + jQuery(this).attr('model') + '/per_page/' + jQuery(this).val() + '/back_url/' + jQuery("#back_url").val();
        location.href = '/main/mainAdmin/SessionPerPage' + params;
    });


//    $(".delete").click(function()
//    {
//    	if (confirm('Удалить объект?'))
//    	{
//    		return true;
//    	}
//
//    	return false;
//	});
});