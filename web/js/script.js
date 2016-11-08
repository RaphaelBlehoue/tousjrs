$(document).ready(function(){
	
	"use strict";

	/*** Login Dropdown  ***/
	$(".search-post > i").on("click",function(){
	$(".search-post > i") .toggleClass('active')
	$(".search-post > label").slideToggle();
	});

	/*** Menu Opener  ***/
	$(".menu-opener").on("click",function(){
		$(".menu-opener").toggleClass("active");	
		$(".side-menus").toggleClass("active");
	})

	/*** FIXED Menu APPEARS ON SCROLL DOWN ***/	
	$(window).scroll(function() {    
	var scroll = $(window).scrollTop();
	if (scroll >= 30) {
	$(".side-header").addClass("sticky");
	}
	else{
	$(".side-header").removeClass("sticky");
	$(".side-header").addClass("");
	}
	});	


	/*** Slide menu Megamenu  ***/
	var elementOffset; 
	$(".slide-menu-sec > nav > ul > li").each(function() {
		elementOffset = $(this).offset().top; 
		console.log(elementOffset);
		$(this).find(".mega-menu").css({
			"top":elementOffset
		});
	});
	$('.slide-menu-sec').scroll(function() { 
		$(".slide-menu-sec > nav > ul > li").each(function() {
			console.log(elementOffset);
			elementOffset = $(this).offset().top; 
			var scrollTop     = $(window).scrollTop();
			var distance      = (elementOffset - scrollTop);
			$(this).find(".mega-menu").css({
				"top":distance
			});
		});
	});

	$(window).scroll(function() { 
		$(".slide-menu-sec > nav > ul > li").each(function() {
			console.log(elementOffset);
			elementOffset = $(this).offset().top; 
			var scrollTop     = $(window).scrollTop();
			var distance      = (elementOffset - scrollTop);
			$(this).find(".mega-menu").css({
				"top":distance
			});
		});

		var scroll = $(window).scrollTop();
		   if (scroll >= 100) {
		        $(".stick").addClass("sticky-header light");
		   }
		   else {
		        $(".stick").removeClass("sticky-header light");
		   }
		   var scroll = $(window).scrollTop();
		   if (scroll >= 100) {
		        $(".stick.minimal .logo-menu").addClass("dark1");
		   }
		   else {
		        $(".stick.minimal .logo-menu").removeClass("dark1");
		   }
	});


	// Resposive Header //
	
	$(".responsive-btn").on("click",function(){
	    $(".responsive-menu").addClass("slidein");
	    return false;
	});
	$(".close-btn").on("click",function(){
	    $(".responsive-menu").removeClass("slidein");
	});
	$(".responsive-menu").on("click",function(e){
	    e.stopPropagation();
	});
	$(".responsive-menu li.menu-item-has-children > a").on("click",function(){
	    $(this).parent().siblings().children("ul").slideUp();
	    $(this).parent().siblings().removeClass("active");
	    $(this).parent().children("ul").slideToggle();
	    $(this).parent().toggleClass("active");
	    return false;
	});

	$(".responsive-btn").on("click",function(){
	    $(".responsive-header").addClass("move");
	    return false;
	});
	$(".close-btn").on("click",function(){
	    $(".responsive-header").removeClass("move");
	});

	// Fashion Art Carousel //
	$('.fashion-art').addClass('active');
		$('.fashion-carousel-controls li').on('click', function() {
		    $(this).addClass('active').siblings().removeClass('active');
	});

	$(".header-post-carousel").owlCarousel({
		autoplay:true,
		autoplayTimeout:3000,
		smartSpeed:2000,
		loop:true,
		dots:false,
		nav:true,
		margin:5,
		items:4,
		singleItem:true,
		responsiveClass:true,
	    responsive:{
	        0:{
	            items:1,
	            nav:true
	        },
	        600:{
	            items:2,
	            nav:false
	        },
	        900:{
	            items:4,
	            nav:true,
	            loop:false
	        },
	        1280:{
	            items:4,
	            nav:true,
	            loop:false
	        }

	    }
	});	

	// Scroll Bar //
	$(function() {
	    $('.responsive-menu').perfectScrollbar();
	});		

});

