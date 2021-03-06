/* jQuery Form Styler v1.6.2 | (c) Dimox | https://github.com/Dimox/jQueryFormStyler */
(function(c){"function"===typeof define&&define.amd?define(["jquery"],c):"object"===typeof exports?module.exports=c(require("jquery")):c(jQuery)})(function(c){c.fn.styler=function(z){var d=c.extend({wrapper:"form",idSuffix:"-styler",filePlaceholder:"\u0424\u0430\u0439\u043b \u043d\u0435 \u0432\u044b\u0431\u0440\u0430\u043d",fileBrowse:"\u041e\u0431\u0437\u043e\u0440...",selectPlaceholder:"\u0412\u044b\u0431\u0435\u0440\u0438\u0442\u0435...",selectSearch:!1,selectSearchLimit:10,selectSearchNotFound:"\u0421\u043e\u0432\u043f\u0430\u0434\u0435\u043d\u0438\u0439 \u043d\u0435 \u043d\u0430\u0439\u0434\u0435\u043d\u043e",
selectSearchPlaceholder:"\u041f\u043e\u0438\u0441\u043a...",selectVisibleOptions:0,singleSelectzIndex:"100",selectSmartPositioning:!0,onSelectOpened:function(){},onSelectClosed:function(){},onFormStyled:function(){}},z);return this.each(function(){function B(){var c="",v="",b="",m="";void 0!==a.attr("id")&&""!==a.attr("id")&&(c=' id="'+a.attr("id")+d.idSuffix+'"');void 0!==a.attr("title")&&""!==a.attr("title")&&(v=' title="'+a.attr("title")+'"');void 0!==a.attr("class")&&""!==a.attr("class")&&(b=
" "+a.attr("class"));var e=a.data(),g;for(g in e)""!==e[g]&&(m+=" data-"+g+'="'+e[g]+'"');this.id=c+m;this.title=v;this.classes=b}var a=c(this),F=navigator.userAgent.match(/(iPad|iPhone|iPod)/i)&&!navigator.userAgent.match(/(Windows\sPhone)/i)?!0:!1,z=navigator.userAgent.match(/Android/i)&&!navigator.userAgent.match(/(Windows\sPhone)/i)?!0:!1;if(a.is(":checkbox"))a.each(function(){if(1>a.parent("div.jq-checkbox").length){var d=function(){var d=new B,b=c("<div"+d.id+' class="jq-checkbox'+d.classes+
'"'+d.title+'><div class="jq-checkbox__div"></div></div>');a.css({position:"absolute",zIndex:"-1",opacity:0,margin:0,padding:0}).after(b).prependTo(b);b.attr("unselectable","on").css({"-webkit-user-select":"none","-moz-user-select":"none","-ms-user-select":"none","-o-user-select":"none","user-select":"none",display:"inline-block",position:"relative",overflow:"hidden"});a.is(":checked")&&b.addClass("checked");a.is(":disabled")&&b.addClass("disabled");b.on("click.styler",function(){b.is(".disabled")||
(a.is(":checked")?(a.prop("checked",!1),b.removeClass("checked")):(a.prop("checked",!0),b.addClass("checked")),a.change());return!1});a.closest("label").add('label[for="'+a.attr("id")+'"]').click(function(a){c(a.target).is("a")||(b.click(),a.preventDefault())});a.on("change.styler",function(){a.is(":checked")?b.addClass("checked"):b.removeClass("checked")}).on("keydown.styler",function(a){32==a.which&&b.click()}).on("focus.styler",function(){b.is(".disabled")||b.addClass("focused")}).on("blur.styler",
function(){b.removeClass("focused")})};d();a.on("refresh",function(){a.off(".styler").parent().before(a).remove();d()})}});else if(a.is(":radio"))a.each(function(){if(1>a.parent("div.jq-radio").length){var u=function(){var v=new B,b=c("<div"+v.id+' class="jq-radio'+v.classes+'"'+v.title+'><div class="jq-radio__div"></div></div>');a.css({position:"absolute",zIndex:"-1",opacity:0,margin:0,padding:0}).after(b).prependTo(b);b.attr("unselectable","on").css({"-webkit-user-select":"none","-moz-user-select":"none",
"-ms-user-select":"none","-o-user-select":"none","user-select":"none",display:"inline-block",position:"relative"});a.is(":checked")&&b.addClass("checked");a.is(":disabled")&&b.addClass("disabled");b.on("click.styler",function(){b.is(".disabled")||(b.closest(d.wrapper).find('input[name="'+a.attr("name")+'"]').prop("checked",!1).parent().removeClass("checked"),a.prop("checked",!0).parent().addClass("checked"),a.change());return!1});a.closest("label").add('label[for="'+a.attr("id")+'"]').click(function(a){c(a.target).is("a")||
(b.click(),a.preventDefault())});a.on("change.styler",function(){a.parent().addClass("checked")}).on("focus.styler",function(){b.is(".disabled")||b.addClass("focused")}).on("blur.styler",function(){b.removeClass("focused")})};u();a.on("refresh",function(){a.off(".styler").parent().before(a).remove();u()})}});else if(a.is(":file"))a.css({position:"absolute",top:0,right:0,width:"100%",height:"100%",opacity:0,margin:0,padding:0}).each(function(){if(1>a.parent("div.jq-file").length){var u=function(){var v=
new B,b=a.data("placeholder");void 0===b&&(b=d.filePlaceholder);var m=a.data("browse");if(void 0===m||""===m)m=d.fileBrowse;var e=c("<div"+v.id+' class="jq-file'+v.classes+'"'+v.title+' style="display: inline-block; position: relative; overflow: hidden"></div>'),g=c('<div class="jq-file__name">'+b+"</div>").appendTo(e);c('<div class="jq-file__browse">'+m+"</div>").appendTo(e);a.after(e);e.append(a);a.is(":disabled")&&e.addClass("disabled");a.on("change.styler",function(){var c=a.val();if(a.is("[multiple]"))for(var c=
"",G=a[0].files,p=0;p<G.length;p++)c+=(0<p?", ":"")+G[p].name;g.text(c.replace(/.+[\\\/]/,""));""===c?(g.text(b),e.removeClass("changed")):e.addClass("changed")}).on("focus.styler",function(){e.addClass("focused")}).on("blur.styler",function(){e.removeClass("focused")}).on("click.styler",function(){e.removeClass("focused")})};u();a.on("refresh",function(){a.off(".styler").parent().before(a).remove();u()})}});else if(a.is("select"))a.each(function(){if(1>a.parent("div.jqselect").length){var u=function(){function v(a){a.off("mousewheel DOMMouseScroll").on("mousewheel DOMMouseScroll",
function(a){var b=null;"mousewheel"==a.type?b=-1*a.originalEvent.wheelDelta:"DOMMouseScroll"==a.type&&(b=40*a.originalEvent.detail);b&&(a.stopPropagation(),a.preventDefault(),c(this).scrollTop(b+c(this).scrollTop()))})}function b(){for(var a=0,c=g.length;a<c;a++){var b="",d="",e=b="",t="",q="",y="";g.eq(a).prop("selected")&&(d="selected sel");g.eq(a).is(":disabled")&&(d="disabled");g.eq(a).is(":selected:disabled")&&(d="selected sel disabled");void 0!==g.eq(a).attr("class")&&(t=" "+g.eq(a).attr("class"),
y=' data-jqfs-class="'+g.eq(a).attr("class")+'"');var x=g.eq(a).data(),f;for(f in x)""!==x[f]&&(e+=" data-"+f+'="'+x[f]+'"');""!==d+t&&(b=' class="'+d+t+'"');b="<li"+y+e+b+">"+g.eq(a).html()+"</li>";g.eq(a).parent().is("optgroup")&&(void 0!==g.eq(a).parent().attr("class")&&(q=" "+g.eq(a).parent().attr("class")),b="<li"+y+' class="'+d+t+" option"+q+'">'+g.eq(a).html()+"</li>",g.eq(a).is(":first-child")&&(b='<li class="optgroup'+q+'">'+g.eq(a).parent().attr("label")+"</li>"+b));u+=b}}function m(){var e=
new B,p="",n=a.data("placeholder"),h=a.data("search"),m=a.data("search-limit"),t=a.data("search-not-found"),q=a.data("search-placeholder"),y=a.data("z-index"),x=a.data("smart-positioning");void 0===n&&(n=d.selectPlaceholder);if(void 0===h||""===h)h=d.selectSearch;if(void 0===m||""===m)m=d.selectSearchLimit;if(void 0===t||""===t)t=d.selectSearchNotFound;void 0===q&&(q=d.selectSearchPlaceholder);if(void 0===y||""===y)y=d.singleSelectzIndex;if(void 0===x||""===x)x=d.selectSmartPositioning;var f=c("<div"+
e.id+' class="jq-selectbox jqselect'+e.classes+'" style="display: inline-block; position: relative; z-index:'+y+'"><div class="jq-selectbox__select"'+e.title+' style="position: relative"><div class="jq-selectbox__select-text"></div><div class="jq-selectbox__trigger"><div class="jq-selectbox__trigger-arrow"></div></div></div></div>');a.css({margin:0,padding:0}).after(f).prependTo(f);var H=c("div.jq-selectbox__select",f),w=c("div.jq-selectbox__select-text",f),e=g.filter(":selected");b();h&&(p='<div class="jq-selectbox__search"><input type="search" autocomplete="off" placeholder="'+
q+'"></div><div class="jq-selectbox__not-found">'+t+"</div>");var k=c('<div class="jq-selectbox__dropdown" style="position: absolute">'+p+'<ul style="position: relative; list-style: none; overflow: auto; overflow-x: hidden">'+u+"</ul></div>");f.append(k);var r=c("ul",k),l=c("li",k),A=c("input",k),z=c("div.jq-selectbox__not-found",k).hide();l.length<m&&A.parent().hide();""===a.val()?w.text(n).addClass("placeholder"):w.text(e.text());var C=0,I=0;l.each(function(){var a=c(this);a.css({display:"inline-block"});
a.innerWidth()>C&&(C=a.innerWidth(),I=a.width());a.css({display:""})});p=f.clone().appendTo("body").width("auto");h=p.find("select").outerWidth();p.remove();h==f.width()&&w.width(I);C>f.width()&&k.width(C);w.is(".placeholder")&&w.width()>C&&w.width(w.width());""===g.first().text()&&""!==a.data("placeholder")&&l.first().hide();a.css({position:"absolute",left:0,top:0,width:"100%",height:"100%",opacity:0});var J=f.outerHeight(),D=A.outerHeight(),E=r.css("max-height"),p=l.filter(".selected");1>p.length&&
l.first().addClass("selected sel");void 0===l.data("li-height")&&l.data("li-height",l.outerHeight());var K=k.css("top");"auto"==k.css("left")&&k.css({left:0});"auto"==k.css("top")&&k.css({top:J});k.hide();p.length&&(g.first().text()!=e.text()&&f.addClass("changed"),f.data("jqfs-class",p.data("jqfs-class")),f.addClass(p.data("jqfs-class")));if(a.is(":disabled"))return f.addClass("disabled"),!1;H.click(function(){c("div.jq-selectbox").filter(".opened").length&&d.onSelectClosed.call(c("div.jq-selectbox").filter(".opened"));
a.focus();if(!F){var b=c(window),q=l.data("li-height"),e=f.offset().top,p=b.height()-J-(e-b.scrollTop()),h=a.data("visible-options");if(void 0===h||""===h)h=d.selectVisibleOptions;var n=5*q,m=q*h;0<h&&6>h&&(n=m);0===h&&(m="auto");var h=function(){k.height("auto").css({bottom:"auto",top:K});var a=function(){r.css("max-height",Math.floor((p-20-D)/q)*q)};a();r.css("max-height",m);"none"!=E&&r.css("max-height",E);p<k.outerHeight()+20&&a()},w=function(){k.height("auto").css({top:"auto",bottom:K});var a=
function(){r.css("max-height",Math.floor((e-b.scrollTop()-20-D)/q)*q)};a();r.css("max-height",m);"none"!=E&&r.css("max-height",E);e-b.scrollTop()-20<k.outerHeight()+20&&a()};!0===x||1===x?p>n+D+20?(h(),f.removeClass("dropup").addClass("dropdown")):(w(),f.removeClass("dropdown").addClass("dropup")):(!1===x||0===x)&&p>n+D+20&&(h(),f.removeClass("dropup").addClass("dropdown"));f.offset().left+k.outerWidth()>b.width()&&k.css({left:"auto",right:0});c("div.jqselect").css({zIndex:y-1}).removeClass("opened");
f.css({zIndex:y});k.is(":hidden")?(c("div.jq-selectbox__dropdown:visible").hide(),k.show(),f.addClass("opened focused"),d.onSelectOpened.call(f)):(k.hide(),f.removeClass("opened dropup dropdown"),c("div.jq-selectbox").filter(".opened").length&&d.onSelectClosed.call(f));A.length&&(A.val("").keyup(),z.hide(),A.keyup(function(){var b=c(this).val();l.each(function(){c(this).html().match(new RegExp(".*?"+b+".*?","i"))?c(this).show():c(this).hide()});""===g.first().text()&&""!==a.data("placeholder")&&l.first().hide();
1>l.filter(":visible").length?z.show():z.hide()}));l.filter(".selected").length&&(""===a.val()?r.scrollTop(0):(0!==r.innerHeight()/q%2&&(q/=2),r.scrollTop(r.scrollTop()+l.filter(".selected").position().top-r.innerHeight()/2+q)));v(r);return!1}});l.hover(function(){c(this).siblings().removeClass("selected")});l.filter(".selected").text();l.filter(".selected").text();l.filter(":not(.disabled):not(.optgroup)").click(function(){a.focus();var b=c(this),q=b.text();if(!b.is(".selected")){var e=b.index(),
e=e-b.prevAll(".optgroup").length;b.addClass("selected sel").siblings().removeClass("selected sel");g.prop("selected",!1).eq(e).prop("selected",!0);w.text(q);f.data("jqfs-class")&&f.removeClass(f.data("jqfs-class"));f.data("jqfs-class",b.data("jqfs-class"));f.addClass(b.data("jqfs-class"));a.change()}k.hide();f.removeClass("opened dropup dropdown");d.onSelectClosed.call(f)});k.mouseout(function(){c("li.sel",k).addClass("selected")});a.on("change.styler",function(){w.text(g.filter(":selected").text()).removeClass("placeholder");
l.removeClass("selected sel").not(".optgroup").eq(a[0].selectedIndex).addClass("selected sel");g.first().text()!=l.filter(".selected").text()?f.addClass("changed"):f.removeClass("changed")}).on("focus.styler",function(){f.addClass("focused");c("div.jqselect").not(".focused").removeClass("opened dropup dropdown").find("div.jq-selectbox__dropdown").hide()}).on("blur.styler",function(){f.removeClass("focused")}).on("keydown.styler keyup.styler",function(c){var b=l.data("li-height");""===a.val()?w.text(n).addClass("placeholder"):
w.text(g.filter(":selected").text());l.removeClass("selected sel").not(".optgroup").eq(a[0].selectedIndex).addClass("selected sel");if(38==c.which||37==c.which||33==c.which||36==c.which)""===a.val()?r.scrollTop(0):r.scrollTop(r.scrollTop()+l.filter(".selected").position().top);40!=c.which&&39!=c.which&&34!=c.which&&35!=c.which||r.scrollTop(r.scrollTop()+l.filter(".selected").position().top-r.innerHeight()+b);13==c.which&&(c.preventDefault(),k.hide(),f.removeClass("opened dropup dropdown"),d.onSelectClosed.call(f))}).on("keydown.styler",
function(a){32==a.which&&(a.preventDefault(),H.click())});c(document).on("click",function(a){c(a.target).parents().hasClass("jq-selectbox")||"OPTION"==a.target.nodeName||(c("div.jq-selectbox").filter(".opened").length&&d.onSelectClosed.call(c("div.jq-selectbox").filter(".opened")),A.length&&A.val("").keyup(),k.hide().find("li.sel").addClass("selected"),f.removeClass("focused opened dropup dropdown"))})}function e(){var e=new B,d=c("<div"+e.id+' class="jq-select-multiple jqselect'+e.classes+'"'+e.title+
' style="display: inline-block; position: relative"></div>');a.css({margin:0,padding:0}).after(d);b();d.append("<ul>"+u+"</ul>");var n=c("ul",d).css({position:"relative","overflow-x":"hidden","-webkit-overflow-scrolling":"touch"}),h=c("li",d).attr("unselectable","on"),e=a.attr("size"),m=n.outerHeight(),t=h.outerHeight();void 0!==e&&0<e?n.css({height:t*e}):n.css({height:4*t});m>d.height()&&(n.css("overflowY","scroll"),v(n),h.filter(".selected").length&&n.scrollTop(n.scrollTop()+h.filter(".selected").position().top));
a.prependTo(d).css({position:"absolute",left:0,top:0,width:"100%",height:"100%",opacity:0});if(a.is(":disabled"))d.addClass("disabled"),g.each(function(){c(this).is(":selected")&&h.eq(c(this).index()).addClass("selected")});else if(h.filter(":not(.disabled):not(.optgroup)").click(function(b){a.focus();var d=c(this);b.ctrlKey||b.metaKey||d.addClass("selected");b.shiftKey||d.addClass("first");b.ctrlKey||b.metaKey||b.shiftKey||d.siblings().removeClass("selected first");if(b.ctrlKey||b.metaKey)d.is(".selected")?
d.removeClass("selected first"):d.addClass("selected first"),d.siblings().removeClass("first");if(b.shiftKey){var e=!1,f=!1;d.siblings().removeClass("selected").siblings(".first").addClass("selected");d.prevAll().each(function(){c(this).is(".first")&&(e=!0)});d.nextAll().each(function(){c(this).is(".first")&&(f=!0)});e&&d.prevAll().each(function(){if(c(this).is(".selected"))return!1;c(this).not(".disabled, .optgroup").addClass("selected")});f&&d.nextAll().each(function(){if(c(this).is(".selected"))return!1;
c(this).not(".disabled, .optgroup").addClass("selected")});1==h.filter(".selected").length&&d.addClass("first")}g.prop("selected",!1);h.filter(".selected").each(function(){var a=c(this),b=a.index();a.is(".option")&&(b-=a.prevAll(".optgroup").length);g.eq(b).prop("selected",!0)});a.change()}),g.each(function(a){c(this).data("optionIndex",a)}),a.on("change.styler",function(){h.removeClass("selected");var a=[];g.filter(":selected").each(function(){a.push(c(this).data("optionIndex"))});h.not(".optgroup").filter(function(b){return-1<
c.inArray(b,a)}).addClass("selected")}).on("focus.styler",function(){d.addClass("focused")}).on("blur.styler",function(){d.removeClass("focused")}),m>d.height())a.on("keydown.styler",function(a){38!=a.which&&37!=a.which&&33!=a.which||n.scrollTop(n.scrollTop()+h.filter(".selected").position().top-t);40!=a.which&&39!=a.which&&34!=a.which||n.scrollTop(n.scrollTop()+h.filter(".selected:last").position().top-n.innerHeight()+2*t)})}var g=c("option",a),u="";a.is("[multiple]")?z||F||e():m()};u();a.on("refresh",
function(){a.off(".styler").parent().before(a).remove();u()})}});else if(a.is(":reset"))a.on("click",function(){setTimeout(function(){a.closest(d.wrapper).find("input, select").trigger("refresh")},1)})}).promise().done(function(){d.onFormStyled.call()})}});


$(function() {

	$('.custom-radio, .custom-checkbox, .custom-select, .custom-select-sort').styler({
		selectSearch: true
	});


	$('.reg-data-check').on( 'change', function() {

		if ($(this).is('.checked')) $(this).closest('.reg-data-fieldset').find('.reg-data-check-content').slideUp('fast');
		else $(this).closest('.reg-data-fieldset').find('.reg-data-check-content').slideDown('fast');

	});



	$('.custom-select-zodiac').on( 'change', function() {


		var zodiac      =  $('.zodiac'),
			zodiac_type  = $('.zodiac-type'),
			zodiac_image = $('.zodiac-image'),
            zodiac_desc = $('.zodiac-desc p'),

			el_day_val   = $('[name = birthday_day]').val(),
			el_month_val = $('[name = birthday_month]').val(),
			el_year_val  = $('[name = birthday_year]').val();


			if ( el_day_val == '' || el_month_val == '' || el_year_val == '' ) {
				console.log('Не все еще выбрано!');
				return false;
			}
			else {
                $.ajax({
                    url: '/site/getznak',
                    data: 'day='+el_day_val+'&month='+el_month_val
                }).success(function(data) {
                    data = eval('(' + data + ')');
                    if (data.result == 'success') {
                        zodiac_type.html(data.znak);
                        zodiac_image.html('<img src="' + data.img + '" alt="' + data.znak + '">');
                        zodiac_desc.html(data.desc);
                    }
                })

				/*if ( el_month_val==01 && el_day_val>=20 || el_month_val==02 && el_day_val<=18 ) {

					zodiac_type.html('Водолей');
					zodiac_image.html('<img src="/images/zodiac_' + 9 +'.png" alt="Водолей">');

					console.log('Водолей');

				}
				if ( el_month_val==02 && el_day_val>=19 || el_month_val==03 && el_day_val<=20 ) {

					zodiac_type.html('Рыбы');
					zodiac_image.html('<img src="/images/zodiac_' + 8 +'.png" alt="Рыбы">');

					console.log('Рыбы');

				}
				if ( el_month_val==03 && el_day_val>=21 || el_month_val==04 && el_day_val<=19 ) {

					zodiac_type.html('Овен');
					zodiac_image.html('<img src="/images/zodiac_' + 1 +'.png" alt="Овен">');

					console.log('Овен');

				}
				if ( el_month_val==04 && el_day_val>=20 || el_month_val==05 && el_day_val<=20 ) { 

					zodiac_type.html('Телец');
					zodiac_image.html('<img src="/images/zodiac_' + 2 +'.png" alt="Телец">');

					console.log('Телец');

				}
				if ( el_month_val==05 && el_day_val>=21 || el_month_val==06 && el_day_val<=21 ) {

					zodiac_type.html('Близнецы');
					zodiac_image.html('<img src="/images/zodiac_' + 3 +'.png" alt="Близнецы">');

					console.log('Близнецы');

				}
				if ( el_month_val==06 && el_day_val>=22 || el_month_val==07 && el_day_val<=22 ) {

					zodiac_type.html('Рак');
					zodiac_image.html('<img src="/images/zodiac_' + 4 +'.png" alt="Рак">');

					console.log('Рак');

				}
				if ( el_month_val==07 && el_day_val>=23 || el_month_val==08 && el_day_val<=22 ) {

					zodiac_type.html('Лев');
					zodiac_image.html('<img src="/images/zodiac_' + 5 +'.png" alt="Лев">');

					console.log('Лев');

				}
				if ( el_month_val==08 && el_day_val>=23 || el_month_val==09 && el_day_val<=22 ) {

					zodiac_type.html('Дева');
					zodiac_image.html('<img src="/images/zodiac_' + 12 +'.png" alt="Дева">');

					console.log('Дева');

				}
				if ( el_month_val==09 && el_day_val>=23 || el_month_val==10 && el_day_val<=22 ) { 

					zodiac_type.html('Весы');
					zodiac_image.html('<img src="/images/zodiac_' + 6 +'.png" alt="Весы">');

					console.log('Весы');

				}
				if ( el_month_val==10 && el_day_val>=23 || el_month_val==11 && el_day_val<=21 ) {

					zodiac_type.html('Скорпион');
					zodiac_image.html('<img src="/images/zodiac_' + 11 +'.png" alt="Скорпион">');

					console.log('Скорпион');

				}
				if ( el_month_val==11 && el_day_val>=22 || el_month_val==12 && el_day_val<=21 ) {

					zodiac_type.html('Стрелец');
					zodiac_image.html('<img src="/images/zodiac_' + 7 +'.png" alt="Стрелец">');

					console.log('Стрелец');

				}
				if ( el_month_val==12 && el_day_val>=22 || el_month_val==01 && el_day_val<=19 ) {

					zodiac_type.html('Козерог');
					zodiac_image.html('<img src="/images/zodiac_' + 10 +'.png" alt="Козерог">');

					console.log('Козерог');

				}*/

				if ( zodiac.is('.is-active') ) {

					zodiac.addClass('flipInX animated').on('animationend webkitAnimationEnd MSAnimationEnd oAnimationEnd', function(){
						$(this).removeClass('flipInX animated');
					});

					return false;

				}
				else {
					$('.zodiac').addClass('is-active zoomInDown animated').on('animationend webkitAnimationEnd MSAnimationEnd oAnimationEnd', function(){
						$(this).removeClass('zoomInDown animated');
					});
				}

				

				return false;
				

				


				// if (zodiac.is('.is-active')) zodiac.addClass('is-active_2')

				// zodiac.addClass('is-active');

				// console.log( el_day_val + '-' + el_month_val + '-' + el_year_val)

			}



	

	});


// szodiac.one('transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd', function(){
// 						$(this).addClass('is-active');
// 					});

	
	

});