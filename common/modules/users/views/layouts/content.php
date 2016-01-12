<?php $this->beginContent('//layouts/main'); ?>

	<div class="sidebar-right">

		<?php echo $content; ?>

	</div>
	<div class="sidebar-left">

        <div class="clear"></div>
<script>
var checkTimer, pubCurLoc;

var fixEncode = function(loc) {
    var h = loc.split('#');
    var l = h[0].split('?');
    return l[0] + (h[1] ? ('#' + h[1]) : '');
}

pubCurLoc = (location.toString().match(/#(.*)/) || {})[1] || '';
if (!pubCurLoc)
	pubCurLoc = (location.pathname || '') + (location.search || '');
else
	pubCurLoc = (location.hash || '');
pubCurLoc = fixEncode(pubCurLoc).replace('#','').replace('/','');


var checker = function() {
	var l = (location.toString().match(/#(.*)/) || {})[1] || '';

	if (!l)
		l = (location.pathname || '') + (location.search || '');
	else
		l = (location.hash || '');
	l = fixEncode(l);
	l = l.replace('#','').replace('/','');
	if(l != pubCurLoc)
	{

		jQuery.ajax({
			'success':function(data){
				$('#catal0g').html(data)
			},
			'url':'/catalog/category/'+(l != 'catalog.htm' && l != 'catalog')? l :'index',
			'cache':false
		});
		pubCurLoc = l;
	}
}


var setLoc = function(loc) {
	curLoc = loc;
	var l = (location.toString().match(/#(.*)/) || {})[1] || '';

	if (!l)
	{
		l = (location.pathname || '') + (location.search || '');
	}

	l = fixEncode(l);

	if (l.replace(/^(\/|!)/, '') != curLoc)
	{
		try {
			history.pushState({}, '', '/' + curLoc);
			pubCurLoc = curLoc;
			return;
		} catch(e) {}
		window.chHashFlag = true;
		location.hash = '#' + curLoc;
		pubCurLoc = curLoc;
	}
}



if('onhashchanged' in window)
	addEventListener("locationchanged", checker, false);
else
	checkTimer = setInterval(checker, 200);


</script>
<?php $this->endContent(); ?>