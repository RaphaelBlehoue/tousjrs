/**
 * Created by raphael on 10/04/2017.
 */

var tdBlocksArray = []; //here we store all the items for the current page

//td_block class - each ajax block uses a object of this class for requests
function tdBlock() {
    this.id = '';
    this.block_type = 1; //block type id (1-234 etc)
    this.atts = '';
    this.td_column_number = '';
    this.td_current_page = 1; //
    this.post_count = 0; //from wp
    this.found_posts = 0; //from wp
    this.max_num_pages = 0; //from wp
    this.td_filter_value = ''; //current live filter value
    this.is_ajax_running = false;
    this.td_user_action = ''; // load more or infinite loader (used by the animation)
    this.header_color = '';
    this.ajax_pagination_infinite_stop = ''; //show load more at page x
}


// td_js_generator - mini detector
(function(){
    var htmlTag = document.getElementsByTagName("html")[0];

    if ( navigator.userAgent.indexOf("MSIE 10.0") > -1 ) {
        htmlTag.className += ' ie10';
    }

    if ( !!navigator.userAgent.match(/Trident.*rv\:11\./) ) {
        htmlTag.className += ' ie11';
    }

    if ( /(iPad|iPhone|iPod)/g.test(navigator.userAgent) ) {
        htmlTag.className += ' td-md-is-ios';
    }

    var user_agent = navigator.userAgent.toLowerCase();
    if ( user_agent.indexOf("android") > -1 ) {
        htmlTag.className += ' td-md-is-android';
    }

    if ( -1 !== navigator.userAgent.indexOf('Mac OS X')  ) {
        htmlTag.className += ' td-md-is-os-x';
    }

    if ( /chrom(e|ium)/.test(navigator.userAgent.toLowerCase()) ) {
        htmlTag.className += ' td-md-is-chrome';
    }

    if ( -1 !== navigator.userAgent.indexOf('Firefox') ) {
        htmlTag.className += ' td-md-is-firefox';
    }

    if ( -1 !== navigator.userAgent.indexOf('Safari') && -1 === navigator.userAgent.indexOf('Chrome') ) {
        htmlTag.className += ' td-md-is-safari';
    }

    if( -1 !== navigator.userAgent.indexOf('IEMobile') ){
        htmlTag.className += ' td-md-is-iemobile';
    }

})();




var tdLocalCache = {};

( function () {
    "use strict";

    tdLocalCache = {
        data: {},
        remove: function (resource_id) {
            delete tdLocalCache.data[resource_id];
        },
        exist: function (resource_id) {
            return tdLocalCache.data.hasOwnProperty(resource_id) && tdLocalCache.data[resource_id] !== null;
        },
        get: function (resource_id) {
            return tdLocalCache.data[resource_id];
        },
        set: function (resource_id, cachedData) {
            tdLocalCache.remove(resource_id);
            tdLocalCache.data[resource_id] = cachedData;
        }
    };
})();



var tds_login_sing_in_widget="show";
var td_viewport_interval_list=[{"limitBottom":767,"sidebarWidth":228},{"limitBottom":1018,"sidebarWidth":300},{"limitBottom":1140,"sidebarWidth":324}];
var td_animation_stack_effect="type0";
var tds_animation_stack=true;
var td_animation_stack_specific_selectors=".entry-thumb, img";
var td_animation_stack_general_selectors=".td-animation-stack img, .post img";
var td_ajax_url="index.html\/\/wp.touslesjours.dev\/wp-admin\/admin-ajax.php?td_theme_name=Newspaper&v=7.7";
var td_get_template_directory_uri="index.html\/\/wp.touslesjours.dev\/wp-content\/themes\/Newspaper%20v7.7\/Newspaper";
var tds_snap_menu="smart_snap_always";
var tds_logo_on_sticky="show_header_logo";
var tds_header_style="";
var td_please_wait="Please wait...";
var td_email_user_pass_incorrect="User or password incorrect!";
var td_email_user_incorrect="Email or username incorrect!";
var td_email_incorrect="Email incorrect!";
var tds_more_articles_on_post_enable="show";
var tds_more_articles_on_post_time_to_wait="";
var tds_more_articles_on_post_pages_distance_from_top=0;
var tds_theme_color_site_wide="#4db2ec";
var tds_smart_sidebar="enabled";
var tdThemeName="Newspaper";
var td_magnific_popup_translation_tPrev="Previous (Left arrow key)";
var td_magnific_popup_translation_tNext="Next (Right arrow key)";
var td_magnific_popup_translation_tCounter="%curr% of %total%";
var td_magnific_popup_translation_ajax_tError="The content from %url% could not be loaded.";
var td_magnific_popup_translation_image_tError="The image #%curr% could not be loaded.";
var td_ad_background_click_link="";
var td_ad_background_click_target="";