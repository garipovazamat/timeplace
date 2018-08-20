$(function() {

	//SVG Fallback
	if(!Modernizr.svg) {
		$("img[src*='svg']").attr("src", function() {
			return $(this).attr("src").replace(".svg", ".png");
		});
	};
	//magnificPopup
	$('.open-popup').magnificPopup({
		type:'inline',
		mainClass: 'mfp-forms'
	});	
	// select-wrap
	$('.select-wrap select:not(.choose_city), .event-form select').styler({
		selectSearch: 'true',
	});
	$('.registration select:not(.choose_city)').styler({
		selectSearch: 'true',
		selectSearchLimit: '2',
	});
	$('.checkbox, .search-people-form, .radio').styler({});
	$('.input-file').styler({
		fileBrowse: 'Загрузить фото',
	});

	// выпадающее меню
	$(".toggle-menu").click(function(){
		$(this).toggleClass("on");
		$(".main-header .menu-aftor").slideToggle();
		return false;
	});
	// выпадающее меню в сайдбаре
	$(".arow-mnu").click(function(){
		$(".arow-mnu span").toggleClass("down");
		$(".layout-navbar .menu-aftor").slideToggle();
		return false;
	});
  //меню слева	
  $(".toggle-menu").click(function(){
  	$(".footer_wrapper, .main_body").toggleClass("open-sidebar", 800);
  	return false;
  });
	// политика конф
	$(".activ-box").click(function () {
		$(".hid-box").slideToggle("slow");
	});  	 	 

	//Chrome Smooth Scroll
	try {
		$.browserSelector();
		if($("html").hasClass("chrome")) {
			$.smoothScroll();
		}
	} catch(err) {

	};

	$("img, a").on("dragstart", function(event) { event.preventDefault(); });
	//карусель
	var owl = $(".slide");
	owl.owlCarousel({
		loop: true,
		autoHeight: false,
		margin:10,
		autoplay:true,
		items: 1,
		autoplayTimeout: 40000,
		smartSpeed: 900,
	});
	$(".next_button").click(function(){
		owl.trigger("next.owl.carousel");
	});
	$(".prev_button").click(function(){
		owl.trigger("prev.owl.carousel");
	});		
});

$(window).load(function() {

	$(".loader_inner").fadeOut();
	$(".loader").delay(400).fadeOut("slow");


});
(function($){				
	jQuery.fn.lightTabs = function(options){

		var createTabs = function(){
			tabs = this;
			i = 0;

			showPage = function(i){
				$(tabs).children(".tabs_list").children(".wrap_tabs_content").hide();
				$(tabs).children(".tabs_list").children(".wrap_tabs_content").eq(i).show();
				$(tabs).children(".tabs_list").children("a").children(".wrap_tabs_content").hide();
				$(tabs).children(".tabs_list").children("a").children(".wrap_tabs_content").eq(i).show();
				$(tabs).children("ul").children("a").children("li").removeClass("active");
				$(tabs).children("ul").children("a").children("li").eq(i).addClass("active");
				$(tabs).children("ul").children("li").removeClass("active");
				$(tabs).children("ul").children("li").eq(i).addClass("active");
			}

			//showPage(0);
			$(tabs).children(".tabs_list").children(".wrap_tabs_content").hide();

			$(tabs).children("ul").children("a").children("li").each(function(index, element){
				$(element).attr("data-page", i);
				i++;                        
			});

			$(tabs).children("ul").children("li").each(function(index, element){
				$(element).attr("data-page", i);
				i++;
			});

			$(document).ready(function(){
				if($('.poisk-category li').hasClass('firstshow')){
					showPage(parseInt($('.firstshow').attr('data-page')));
				}

				if($('.tabs_controls > li').hasClass('tabs_controls-item')){
					showPage(0);
				}
			});


			$(tabs).children("ul").children("li").click(function(){
				showPage(parseInt($(this).attr("data-page")));
			});				
		};		
		return this.each(createTabs);
	};	
})(jQuery);
$(document).ready(function(){
	$(".tabs, .tabs-cat").lightTabs();
	//////////////	
	/*$.datetimepicker.setLocale('ru');
	$("#date-start, #date-end").datetimepicker({
		timepicker:false,
		format: 'd/m/Y',
		minDate: 0
	});*/
	$('.calendar-for-date-start').on('click', function () {
	    $('#date-start').datetimepicker('show');
	});
	$('.calendar-for-date-end').on('click', function () {
	    $('#date-end').datetimepicker('show');
	});
	//////////////////
	/*$("#time-start, #time-end").datetimepicker({
		datepicker:false,
		step:10
	});
	$(".clock-for-time-start").on('click', function () {
	    $("#time-start").datetimepicker('show');
	});
	$(".clock-for-time-end").on('click', function () {
	    $("#time-end").datetimepicker('show');
	});
	//
	$("#birth").datetimepicker({
		timepicker:false,
		format: 'd/m/Y'
	});
	$(".calendar-for-birth").on('click', function () {
	    $("#birth").datetimepicker('show');
	});*/
});