(function(){
   $.ajax({
      url : Routing.generate('ajax_menu_get'),
      type: "GET",
      dataType: 'json',
      cache: false,
      success : function (data) {
         loadTemplatePrepend('#template-menu', data, '#menu-td-demo-header-menu-2');
         loadTemplatePrepend('#template-menu-mobile', data, '#menu-td-demo-header-menu');
      },
      error: function (xhr, status, errorThrown) {
         alert( "Sorry, there was a problem!" );
         console.log( "Error: " + errorThrown );
         console.log( "Status: " + status );
         console.dir( xhr );
      }
   });
   jQuery.timeago.settings.strings = {
      // environ ~= about, it's optional
      prefixAgo: "il y a",
      prefixFromNow: "d'ici",
      seconds: "moins d'une minute",
      minute: "environ une minute",
      minutes: "environ %d minutes",
      hour: "environ une heure",
      hours: "environ %d heures",
      day: "environ un jour",
      days: "environ %d jours",
      month: "environ un mois",
      months: "environ %d mois",
      year: "un an",
      years: "%d ans"
   };
   $(".timeago").timeago();
})();

function loadTemplate(elt, data, output) {
   Mustache.tags = ['[[', ']]'];
   var template = $(elt).html();
   Mustache.parse(template);
   var rendered = Mustache.render(template, data);
   $(output).html(rendered);
}

function loadTemplatePrepend(elt, data, output) {
   Mustache.tags = ['[[', ']]'];
   var template = $(elt).html();
   Mustache.parse(template);
   var rendered = Mustache.render(template, data);
   $(rendered).prependTo(output);
}