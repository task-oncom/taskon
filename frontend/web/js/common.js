 $(document).ready(function() {
	$.parallaxify();

	$(".player").mb_YTPlayer();

	$("a.scrollto").mPageScroll2id();

	$(".tab_item").not(":first").hide();
	$(".sect_map .tab_map").click(function() {
		$(".sect_map .tab_map").removeClass("active").eq($(this).index()).addClass("active");
		$(".tab_item").hide().eq($(this).index()).fadeIn()
	}).eq(0).addClass("active");

    $(function() {
		$('.mfp-container').hover(function() {
			if($('.txtbtnclose').is(':visible')) {
				$('.txtbtnclose').removeClass('hoverclose'); 
			}
			else {
				$('.txtbtnclose').addClass('hoverclose'); 
			}   
		}); 
	});
    $('.about_hide_block').hide();
	$('.about_hide_btn').click(function(){
		if ($('.about_hide_btn').text() == 'Подробнее'){
			$('.about_hide_block').slideDown(1000);
			$(this).text('Скрыть')
		}
		else {
			$('.about_hide_block').slideUp(1000);
			$(this).text('Подробнее');
		}
	});

	$('.dep_block__hide').hide();
	$('.dep_block_hide__btn').click(function(){
		if ($('.dep_block_hide__btn').text() == 'Смотреть полную схему'){
			$('.line_hide').fadeTo( "slow" , 0);
			$('.dep_block__hide').slideDown(1000);
			$(this).text('Скрыть полную схему')
		}
		else {
			$('.line_hide').fadeTo( "slow" , 1);
			$('.dep_block__hide').slideUp(1000);
			$(this).text('Смотреть полную схему');
		}
	});

	$('.rev_txt_hide').hide();
	$('.rev__hide__btn').click(function(){
		if ($('.rev__hide__btn').text() == 'Подробнее'){
			$('.line_hide_rev').fadeTo( "slow" , 0);
			$('.rev_txt_hide').slideDown(1000);
			$(this).text('Скрыть')
		}
		else {
			$('.line_hide_rev').fadeTo( "slow" , 1);
			$('.rev_txt_hide').slideUp(1000);
			$(this).text('Подробнее');
		}
	});

	$('.list_hide').hide();
	$('.list_hide_btn').click(function(){
		if ($('.list_hide_btn').text() == 'Подробнее'){
			$('.list_hide_rev').fadeTo( "slow" , 0);
			$('.list_hide').slideDown(1000);
			$(this).text('Свернуть')
		}
		else {
			$('.list_hide_rev').fadeTo( "slow" , 1);
			$('.list_hide').slideUp(1000);
			$(this).text('Подробнее');
		}
	});

	$('.list_hide2').hide();
	$('.list_hide2_btn').click(function(){
		if ($('.list_hide2_btn').text() == 'Подробнее'){
			$('.list_hide2_rev').fadeTo( "slow" , 0);
			$('.list_hide2').slideDown(1000);
			$(this).text('Свернуть')
		}
		else {
			$('.list_hide2_rev').fadeTo( "slow" , 1);
			$('.list_hide2').slideUp(1000);
			$(this).text('Подробнее');
		}
	});

	$('.list_hide3').hide();
	$('.list_hide3_btn').click(function(){
		if ($('.list_hide3_btn').text() == 'Подробнее'){
			$('.list_hide3_rev').fadeTo( "slow" , 0);
			$('.list_hide3').slideDown(1000);
			$(this).text('Свернуть')
		}
		else {
			$('.list_hide3_rev').fadeTo( "slow" , 1);
			$('.list_hide3').slideUp(1000);
			$(this).text('Подробнее');
		}
	});

	$('.list_hide4').hide();
	$('.list_hide4_btn').click(function(){
		if ($('.list_hide4_btn').text() == 'Подробнее'){
			$('.list_hide4_rev').fadeTo( "slow" , 0);
			$('.list_hide4').slideDown(1000);
			$(this).text('Свернуть')
		}
		else {
			$('.list_hide4_rev').fadeTo( "slow" , 1);
			$('.list_hide4').slideUp(1000);
			$(this).text('Подробнее');
		}
	});

	$('.list_hide5').hide();
	$('.list_hide5_btn').click(function(){
		if ($('.list_hide5_btn').text() == 'Подробнее'){
			$('.list_hide5_rev').fadeTo( "slow" , 0);
			$('.list_hide5').slideDown(1000);
			$(this).text('Свернуть')
		}
		else {
			$('.list_hide5_rev').fadeTo( "slow" , 1);
			$('.list_hide5').slideUp(1000);
			$(this).text('Подробнее');
		}
	});

 	$('.mouse').hover(
        function()
        {
            $('.mouse_point').addClass('mouse_hover');
        },
        function()
        {
            $('.mouse_point').removeClass('mouse_hover');
        }
    );
	$('.keis_slider').bxSlider({
		mode: 'fade',
		captions: true,
		auto: true
	});
	$('.keis_slider_txt').bxSlider({
		mode: 'fade',
		captions: true,
		auto: true
	});

	new WOW().init();

	if(!Modernizr.svg) {
		$("img[src*='svg']").attr("src", function() {
			return $(this).attr("src").replace(".svg", ".png");
		});
	};
	
	try {
		$.browserSelector();
		if($("html").hasClass("chrome")) {
			$.smoothScroll();
		}
	} catch(err) {

	};
	$(".toggle_bottom").click(function() {
		$("html, body").animate({ scrollTop: $(".section2").height()+120 }, "slow");
		return false;
	});
	$(".mouse").click(function() {
		$("html, body").animate({ scrollTop: $(".video_block__title").height()+700 }, "slow");
		return false;
	});

	var time = 2, cc = 1;
	$(window).scroll(function(){
		$('#counter').each(function(){
			var
			cPos = $(this).offset().top,
			topWindow = $(window).scrollTop();
			if (cPos < topWindow + 800) {
				if (cc < 2) {
					$('.countdown').each(function(){
				    	var 
				   		i = 1,
				    	num = $(this).data('num'),
				    	step = 1000 * time / num,
				    	that = $(this),
				    	int = setInterval(function(){
				      		if (i <= num) {
				        		that.html(i);
				      		}
				      		else {
				      			cc = cc + 2;
				        		clearInterval(int);
				      		}
				      		i++;
				    	},step);
				  	});
				}
				
		  	}
		});
	});
	

    $(".section3").waypoint(function() {

		$(".section3 .mackup_item1").each(function(index) {
			var ths = $(this);
			setInterval(function() {
				ths.removeClass("mackup_item1_off").addClass("mackup_item1_on");
			}, 200*index);
		});
		$(".section3 .mackup_item2").each(function(index) {
			var ths = $(this);
			setInterval(function() {
				ths.removeClass("mackup_item2_off").addClass("mackup_item2_on");
			}, 200*index);
		});
		$(".section3 .mackup_item3").each(function(index) {
			var ths = $(this);
			setInterval(function() {
				ths.removeClass("mackup_item3_off").addClass("mackup_item3_on");
			}, 200*index);
		});
		$(".section3 .txt_interface").each(function(index) {
			var ths = $(this);
			setInterval(function() {
				ths.removeClass("txt_interface_off").addClass("txt_interface_on");
			}, 200*index);
		});
		$(".section3 .txt_admin").each(function(index) {
			var ths = $(this);
			setInterval(function() {
				ths.removeClass("txt_admin_off").addClass("txt_admin_on");
			}, 200*index);
		});
		$(".section3 .txt_lock").each(function(index) {
			var ths = $(this);
			setInterval(function() {
				ths.removeClass("txt_lock_off").addClass("txt_lock_on");
			}, 200*index);
		});
		$(".section3 .txt_server").each(function(index) {
			var ths = $(this);
			setInterval(function() {
				ths.removeClass("txt_server_off").addClass("txt_server_on");
			}, 200*index);
		});

	}, {
		offset : "20%"
	});

	$(".section4 .ul_check").waypoint(function() {

		$(".section4 .puls").each(function(index) {
			var ths = $(this);
			setInterval(function() {
				ths.removeClass("puls_off").addClass("puls_on");
			}, 200*index);
		});
		$(".section4 .puls_point").each(function(index) {
			var ths = $(this);
			setInterval(function() {
				ths.removeClass("puls_point_off").addClass("puls_point_on");
			}, 200*index);
		});

	}, {
		offset : "70%"
	});

	$(".file-upload input[type=file]").change(function(){
         var filename = $(this).val().replace(/.*\\/, "");
         $("#filename").val(filename);
    });
	$('.popup-form').magnificPopup({
		type: 'inline',

		fixedContentPos: false,
		fixedBgPos: true,

		overflowY: 'auto',

		closeBtnInside: true,
		preloader: false,
		
		midClick: true,
		removalDelay: 300,
		mainClass: 'my-mfp-zoom-in'
	});
	$(".toggle-mnu").click(function() {
		$(this).toggleClass("on");
		$(".main-mnu").slideToggle();
		return false;
	});
	$(function() {
		$('.top_phone').hover(function() {
			if($('.phone_hover_head').is(':visible')) {
				$('.phone_hover_head').removeClass('show_phone_inf_head'); 
			}
			else {
				$('.phone_hover_head').addClass('show_phone_inf_head'); 
			}   
		}); 
	});
	
	$(function() {
		$('.foot_phone').hover(function() {
			if($('.phone_hover_foot').is(':visible')) {
				$('.phone_hover_foot').removeClass('show_phone_inf'); 
			}
			else {
				$('.phone_hover_foot').addClass('show_phone_inf'); 
			}   
		}); 
	});
	$(function() {
		$('.d_menu').click(function() {
			if($('.d_menu_hide').is(':visible')) {
				$('.d_menu_hide').removeClass('show_d_menu'); 
			}
			else {
				$('.d_menu_hide').addClass('show_d_menu'); 
			}   
		}); 
	});
	$(function () {
		window.validation.init({
			container: '.valid_form',
		});
	});
	$(function () {
		window.validation.init({
			container: '.footer_form',
		});
	});
	$(function () {
		window.validation.init({
			container: '.sect_cont_form',
		});
	});
	$(function () {
		window.validation.init({
			container: '.validreg_form',
		});
	});
	$(".toggle-mnu").click(function () {
    	$(".menu").toggleClass("menu_active");
    });
    $(".link").click(function () {
    	$(".menu").removeClass("menu_active");
    	$(".toggle-mnu").removeClass("on");
    });

});

$(window).load(function() {

	$(".loader_inner").fadeOut();
	$(".loader").delay(400).fadeOut("slow");

});
 
$(window).scroll(function() {
	var st = $(this).scrollTop();

	$(".p5").css({
		"transform" : "translate(0%, -" + st *1 + "%"
	});
	$(".p6").css({
		"transform" : "translate(0%, -" + st + "%"
	});
	$(".p7").css({
		"transform" : "translate(0%, -" + st /0.8 + "%"
	});
});
jQuery(function($){
	$(document).mouseup(function (e){
		var div = $(".d_menu_hide");
		if (!div.is(e.target)
		    && div.has(e.target).length === 0) {
			$('.d_menu_hide').removeClass('show_d_menu'); 
		}
	});
});

$('.txt_interface').hover(
	function()
	{
		$('.mackup_item1').addClass('mackup_hover1');
	},
	function()
	{
		$('.mackup_item1').removeClass('mackup_hover1');
	}
);
$('.txt_admin').hover(
	function()
	{
		$('.mackup_item2').addClass('mackup_hover2');
	},
	function()
	{
		$('.mackup_item2').removeClass('mackup_hover2');
	}
);
$('.txt_lock').hover(
	function()
	{
		$('.mackup_item3').addClass('mackup_hover3');
	},
	function()
	{
		$('.mackup_item3').removeClass('mackup_hover3');
	}
);

$('.txt_server').hover(
	function()
	{
		$('.set1').addClass('set1_hover');
	},
	function()
	{
		$('.set1').removeClass('set1_hover');
	}
);
$('.txt_server').hover(
	function()
	{
		$('.set2').addClass('set2_hover');
	},
	function()
	{
		$('.set2').removeClass('set2_hover');
	}
);
$('.txt_server').hover(
	function()
	{
		$('.set3').addClass('set3_hover');
	},
	function()
	{
		$('.set3').removeClass('set3_hover');
	}
);
$('.txt_server').hover(
	function()
	{
		$('.set4').addClass('set4_hover');
	},
	function()
	{
		$('.set4').removeClass('set4_hover');
	}
);
$('.txt_server').hover(
	function()
	{
		$('.set5').addClass('set5_hover');
	},
	function()
	{
		$('.set5').removeClass('set5_hover');
	}
);
$('.txt_server').hover(
	function()
	{
		$('.set6').addClass('set6_hover');
	},
	function()
	{
		$('.set6').removeClass('set6_hover');
	}
);
$('.txt_server').hover(
	function()
	{
		$('.set7').addClass('set7_hover');
	},
	function()
	{
		$('.set7').removeClass('set7_hover');
	}
);