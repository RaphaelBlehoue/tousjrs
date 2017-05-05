/**
 * Created by raphael on 11/04/2017.
 */

// if the theme has tdBackstr support, it means this already uses it
if ( 'undefined' !== typeof window.tdBackstr ) {

    (function(){
        // the site background td-backstretch jquery object is dynamically added in DOM, and after any translation effects are applied over td-backstretch
        var wrapper_image_jquery_obj = jQuery( '<div class=\'backstretch\'></div>' );
        var image_jquery_obj = jQuery( '<img class=\'td-backstretch not-parallax\' src=\'wp-content/uploads/2017/04/blue-girl-300x175.jpg\'>' );

        wrapper_image_jquery_obj.append( image_jquery_obj );

        jQuery( 'body' ).prepend( wrapper_image_jquery_obj );

        var td_backstr_item = new tdBackstr.item();

        td_backstr_item.wrapper_image_jquery_obj = wrapper_image_jquery_obj;
        td_backstr_item.image_jquery_obj = image_jquery_obj;

        tdBackstr.add_item( td_backstr_item );

    })();
}





jQuery().ready(function () {
    tdWeather.addItem({"block_uid":"td_top_weather_uid","location":"New York","api_location":"New York","api_language":"fr","today_icon":"clear-sky-n","today_icon_text":"ensoleill\u00e9","today_temp":[8.8,47.8],"today_humidity":73,"today_wind_speed":[2.7,1.7],"today_min":[5,41],"today_max":[13,55.4],"today_clouds":1,"current_unit":0,"forecast":[{"timestamp":1491926400,"day_temp":[18,64],"day_name":"mar","owm_day_index":2},{"timestamp":1492012800,"day_temp":[16,61],"day_name":"mer","owm_day_index":3},{"timestamp":1492099200,"day_temp":[15,58],"day_name":"jeu","owm_day_index":4},{"timestamp":1492185600,"day_temp":[16,62],"day_name":"ven","owm_day_index":5},{"timestamp":1492272000,"day_temp":[13,55],"day_name":"sam","owm_day_index":6}],"api_key":"17aa0dd026f22e09f52ad000002991c6"});
});





(function(){
    var html_jquery_obj = jQuery('html');

    if (html_jquery_obj.length && (html_jquery_obj.is('.ie8') || html_jquery_obj.is('.ie9'))) {

        var path = 'wp-content/themes/Newspaper%20v7.7/Newspaper/style.css';

        jQuery.get(path, function(data) {

            var str_split_separator = '#td_css_split_separator';
            var arr_splits = data.split(str_split_separator);
            var arr_length = arr_splits.length;

            if (arr_length > 1) {

                var dir_path = 'wp-content/themes/Newspaper%20v7.7/Newspaper/index.html';
                var splited_css = '';

                for (var i = 0; i < arr_length; i++) {
                    if (i > 0) {
                        arr_splits[i] = str_split_separator + ' ' + arr_splits[i];
                    }
                    //jQuery('head').append('<style>' + arr_splits[i] + '</style>');

                    var formated_str = arr_splits[i].replace(/\surl\(\'(?!data\:)/gi, function regex_function(str) {
                        return ' url(\'' + dir_path + '/' + str.replace(/url\(\'/gi, '').replace(/^\s+|\s+$/gm,'');
                    });

                    splited_css += "<style>" + formated_str + "</style>";
                }

                var td_theme_css = jQuery('link#td-theme-css');

                if (td_theme_css.length) {
                    td_theme_css.after(splited_css);
                }
            }
        });
    }
})();