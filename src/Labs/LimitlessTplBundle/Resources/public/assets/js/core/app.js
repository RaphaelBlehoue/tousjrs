/* ------------------------------------------------------------------------------
*
*  # Template JS core
*
*  Core JS file with default functionality configuration
*
*  Version: 1.0
*  Latest update: Aug 1, 2015
*
* ---------------------------------------------------------------------------- */

$(function() {


    // ========================================
    //
    // Layout
    //
    // ========================================


    // Calculate page container height
    // -------------------------

    // Window height - navbars heights
    function containerHeight() {
        var availableHeight = $(window).height() - $('body > .navbar').outerHeight() - $('body > .navbar + .navbar').outerHeight() - $('body > .navbar + .navbar-collapse').outerHeight() - $('.page-header').outerHeight();

        $('.page-container').attr('style', 'min-height:' + availableHeight + 'px');
    }




    // ========================================
    //
    // Heading elements
    //
    // ========================================


    // Heading elements toggler
    // -------------------------

    // Add control button toggler to page and panel headers if have heading elements
    $('.panel-heading, .page-header-content, .panel-body').has('> .heading-elements').append('<a class="heading-elements-toggle"><i class="icon-menu"></i></a>');


    // Toggle visible state of heading elements
    $('.heading-elements-toggle').on('click', function() {
        $(this).parent().children('.heading-elements').toggleClass('visible');
    });



    // Breadcrumb elements toggler
    // -------------------------

    // Add control button toggler to breadcrumbs if has elements
    $('.breadcrumb-line').has('.breadcrumb-elements').append('<a class="breadcrumb-elements-toggle"><i class="icon-menu-open"></i></a>');


    // Toggle visible state of breadcrumb elements
    $('.breadcrumb-elements-toggle').on('click', function() {
        $(this).parent().children('.breadcrumb-elements').toggleClass('visible');
    });




    // ========================================
    //
    // Navbar
    //
    // ========================================


    // Navbar navigation
    // -------------------------

    // Prevent dropdown from closing on click
    $(document).on('click', '.dropdown-content', function (e) {
        e.stopPropagation();
    });

    // Disabled links
    $('.navbar-nav .disabled a').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
    });

    // Show tabs inside dropdowns
    $('.dropdown-content a[data-toggle="tab"]').on('click', function (e) {
        $(this).tab('show')
    });



    // Drill down menu
    // ------------------------------

    // If menu has child levels, add selector class
    $('.menu-list').find('li').has('ul').parents('.menu-list').addClass('has-children');

    // Attach drill down menu to menu list with child levels
    $('.has-children').dcDrilldown({
        defaultText: 'Back to parent',
        saveState: true
    });




    // ========================================
    //
    // Element controls
    //
    // ========================================


    // Reload elements
    // -------------------------

    // Panels
    $('.panel [data-action=reload]').click(function (e) {
        e.preventDefault();
        var block = $(this).parent().parent().parent().parent().parent();
        $(block).block({ 
            message: '<i class="icon-spinner2 spinner"></i>',
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.8,
                cursor: 'wait',
                'box-shadow': '0 0 0 1px #ddd'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'none'
            }
        });

        // For demo purposes
        window.setTimeout(function () {
           $(block).unblock();
        }, 2000); 
    });


    // Sidebar categories
    $('.category-title [data-action=reload]').click(function (e) {
        e.preventDefault();
        var block = $(this).parent().parent().parent().parent();
        $(block).block({ 
            message: '<i class="icon-spinner2 spinner"></i>',
            overlayCSS: {
                backgroundColor: '#000',
                opacity: 0.5,
                cursor: 'wait',
                'box-shadow': '0 0 0 1px #000'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'none',
                color: '#fff'
            }
        });

        // For demo purposes
        window.setTimeout(function () {
           $(block).unblock();
        }, 2000); 
    }); 


    // Light sidebar categories
    $('.sidebar-default .category-title [data-action=reload]').click(function (e) {
        e.preventDefault();
        var block = $(this).parent().parent().parent().parent();
        $(block).block({ 
            message: '<i class="icon-spinner2 spinner"></i>',
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.8,
                cursor: 'wait',
                'box-shadow': '0 0 0 1px #ddd'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'none'
            }
        });

        // For demo purposes
        window.setTimeout(function () {
           $(block).unblock();
        }, 2000); 
    }); 



    // Collapse elements
    // -------------------------

    //
    // Sidebar categories
    //

    // Hide if collapsed by default
    $('.category-collapsed').children('.category-content').hide();


    // Rotate icon if collapsed by default
    $('.category-collapsed').find('[data-action=collapse]').addClass('rotate-180');


    // Collapse on click
    $('.category-title [data-action=collapse]').click(function (e) {
        e.preventDefault();
        var $categoryCollapse = $(this).parent().parent().parent().nextAll();
        $(this).parents('.category-title').toggleClass('category-collapsed');
        $(this).toggleClass('rotate-180');

        containerHeight(); // adjust page height

        $categoryCollapse.slideToggle(150);
    });


    //
    // Panels
    //

    // Hide if collapsed by default
    $('.panel-collapsed').children('.panel-heading').nextAll().hide();


    // Rotate icon if collapsed by default
    $('.panel-collapsed').find('[data-action=collapse]').children('i').addClass('rotate-180');


    // Collapse on click
    $('.panel [data-action=collapse]').click(function (e) {
        e.preventDefault();
        var $panelCollapse = $(this).parent().parent().parent().parent().nextAll();
        $(this).parents('.panel').toggleClass('panel-collapsed');
        $(this).toggleClass('rotate-180');

        containerHeight(); // recalculate page height

        $panelCollapse.slideToggle(150);
    });



    // Remove elements
    // -------------------------

    // Panels
    $('.panel [data-action=close]').click(function (e) {
        e.preventDefault();
        var $panelClose = $(this).parent().parent().parent().parent().parent();

        containerHeight(); // recalculate page height

        $panelClose.slideUp(150, function() {
            $(this).remove();
        });
    });


    // Sidebar categories
    $('.category-title [data-action=close]').click(function (e) {
        e.preventDefault();
        var $categoryClose = $(this).parent().parent().parent().parent();

        containerHeight(); // recalculate page height

        $categoryClose.slideUp(150, function() {
            $(this).remove();
        });
    });




    // ========================================
    //
    // Main navigation
    //
    // ========================================


    // Main navigation
    // -------------------------

    // Add 'active' class to parent list item in all levels
    $('.navigation').find('li.active').parents('li').addClass('active');

    // Hide all nested lists
    $('.navigation').find('li').not('.active, .category-title').has('ul').children('ul').addClass('hidden-ul');

    // Highlight children links
    $('.navigation').find('li').has('ul').children('a').addClass('has-ul');

    // Add active state to all dropdown parent levels
    $('.dropdown-menu:not(.dropdown-content), .dropdown-menu:not(.dropdown-content) .dropdown-submenu').has('li.active').addClass('active').parents('.navbar-nav .dropdown, .navbar-nav .dropup').addClass('active');

    

    // Main navigation tooltips positioning
    // -------------------------

    // Left sidebar
    $('.navigation-main > .navigation-header > i').tooltip({
        placement: 'right',
        container: 'body'
    });



    // Collapsible functionality
    // -------------------------

    // Main navigation
    $('.navigation-main').find('li').has('ul').children('a').on('click', function (e) {
        e.preventDefault();

        // Collapsible
        $(this).parent('li').not('.disabled').not($('.sidebar-xs').not('.sidebar-xs-indicator').find('.navigation-main').children('li')).toggleClass('active').children('ul').slideToggle(250);

        // Accordion
        if ($('.navigation-main').hasClass('navigation-accordion')) {
            $(this).parent('li').not('.disabled').not($('.sidebar-xs').not('.sidebar-xs-indicator').find('.navigation-main').children('li')).siblings(':has(.has-ul)').removeClass('active').children('ul').slideUp(250);
        }
    });

        
    // Alternate navigation
    $('.navigation-alt').find('li').has('ul').children('a').on('click', function (e) {
        e.preventDefault();

        // Collapsible
        $(this).parent('li').not('.disabled').toggleClass('active').children('ul').slideToggle(200);

        // Accordion
        if ($('.navigation-alt').hasClass('navigation-accordion')) {
            $(this).parent('li').not('.disabled').siblings(':has(.has-ul)').removeClass('active').children('ul').slideUp(200);
        }
    }); 




    // ========================================
    //
    // Sidebars
    //
    // ========================================


    // Mini sidebar
    // -------------------------

    // Toggle mini sidebar
    $('.sidebar-main-toggle').on('click', function (e) {
        e.preventDefault();

        // Toggle min sidebar class
        $('body').toggleClass('sidebar-xs');
    });



    // Sidebar controls
    // -------------------------

    // Disable click in disabled navigation items
    $(document).on('click', '.navigation .disabled a', function (e) {
        e.preventDefault();
    });


    // Adjust page height on sidebar control button click
    $(document).on('click', '.sidebar-control', function (e) {
        containerHeight();
    });


    // Hide main sidebar in Dual Sidebar
    $(document).on('click', '.sidebar-main-hide', function (e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-main-hidden');
    });


    // Toggle second sidebar in Dual Sidebar
    $(document).on('click', '.sidebar-secondary-hide', function (e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-secondary-hidden');
    });


    // Hide all sidebars
    $(document).on('click', '.sidebar-all-hide', function (e) {
        e.preventDefault();

        $('body').toggleClass('sidebar-all-hidden');
    });



    //
    // Opposite sidebar
    //

    // Collapse main sidebar if opposite sidebar is visible
    $(document).on('click', '.sidebar-opposite-toggle', function (e) {
        e.preventDefault();

        // Opposite sidebar visibility
        $('body').toggleClass('sidebar-opposite-visible');

        // If visible
        if ($('body').hasClass('sidebar-opposite-visible')) {

            // Make main sidebar mini
            $('body').addClass('sidebar-xs');

            // Hide children lists
            $('.navigation-main').children('li').children('ul').css('display', '');
        }
        else {

            // Make main sidebar default
            $('body').removeClass('sidebar-xs');
        }
    });


    // Hide main sidebar if opposite sidebar is shown
    $(document).on('click', '.sidebar-opposite-main-hide', function (e) {
        e.preventDefault();

        // Opposite sidebar visibility
        $('body').toggleClass('sidebar-opposite-visible');
        
        // If visible
        if ($('body').hasClass('sidebar-opposite-visible')) {

            // Hide main sidebar
            $('body').addClass('sidebar-main-hidden');
        }
        else {

            // Show main sidebar
            $('body').removeClass('sidebar-main-hidden');
        }
    });


    // Hide secondary sidebar if opposite sidebar is shown
    $(document).on('click', '.sidebar-opposite-secondary-hide', function (e) {
        e.preventDefault();

        // Opposite sidebar visibility
        $('body').toggleClass('sidebar-opposite-visible');

        // If visible
        if ($('body').hasClass('sidebar-opposite-visible')) {

            // Hide secondary
            $('body').addClass('sidebar-secondary-hidden');

        }
        else {

            // Show secondary
            $('body').removeClass('sidebar-secondary-hidden');
        }
    });


    // Hide all sidebars if opposite sidebar is shown
    $(document).on('click', '.sidebar-opposite-hide', function (e) {
        e.preventDefault();

        // Toggle sidebars visibility
        $('body').toggleClass('sidebar-all-hidden');

        // If hidden
        if ($('body').hasClass('sidebar-all-hidden')) {

            // Show opposite
            $('body').addClass('sidebar-opposite-visible');

            // Hide children lists
            $('.navigation-main').children('li').children('ul').css('display', '');
        }
        else {

            // Hide opposite
            $('body').removeClass('sidebar-opposite-visible');
        }
    });


    // Keep the width of the main sidebar if opposite sidebar is visible
    $(document).on('click', '.sidebar-opposite-fix', function (e) {
        e.preventDefault();

        // Toggle opposite sidebar visibility
        $('body').toggleClass('sidebar-opposite-visible');
    });



    // Mobile sidebar controls
    // -------------------------

    // Toggle main sidebar
    $('.sidebar-mobile-main-toggle').on('click', function (e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-mobile-main').removeClass('sidebar-mobile-secondary sidebar-mobile-opposite');
    });


    // Toggle secondary sidebar
    $('.sidebar-mobile-secondary-toggle').on('click', function (e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-mobile-secondary').removeClass('sidebar-mobile-main sidebar-mobile-opposite');
    });


    // Toggle opposite sidebar
    $('.sidebar-mobile-opposite-toggle').on('click', function (e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-mobile-opposite').removeClass('sidebar-mobile-main sidebar-mobile-secondary');
    });

    function clearForm() {
        return this.each(function() {
            var type = this.type, tag = this.tagName.toLowerCase();
            if (tag == 'form')
                return $(':input',this).clearForm();
            if (type == 'text' || type == 'password' || tag == 'textarea')
                this.value = '';
            else if (type == 'checkbox' || type == 'radio')
                this.checked = false;
            else if (tag == 'select')
                this.selectedIndex = -1;
        });
    };

    // Mobile sidebar setup
    // -------------------------

    $(window).on('resize', function() {
        setTimeout(function() {
            containerHeight();
            
            if($(window).width() <= 768) {

                // Add mini sidebar indicator
                $('body').addClass('sidebar-xs-indicator');

                // Place right sidebar before content
                $('.sidebar-opposite').prependTo('.page-content');

                // Remove nicescroll on mobiles
                $('.menu-list, .menu-list ul').getNiceScroll().remove();
                $(".menu-list, .menu-list ul").removeAttr('style').removeAttr('tabindex');
            }
            else {

                // Remove mini sidebar indicator
                $('body').removeClass('sidebar-xs-indicator');

                // Revert back right sidebar
                $('.sidebar-opposite').insertAfter('.content-wrapper');

                // Remove all mobile sidebar classes
                $('body').removeClass('sidebar-mobile-main sidebar-mobile-secondary sidebar-mobile-opposite');

                // Initialize nicescroll on tablets+
                $(".menu-list, .menu-list ul").niceScroll({
                    mousescrollstep: 100,
                    cursorcolor: '#ccc',
                    cursorborder: '',
                    cursorwidth: 3,
                    hidecursordelay: 200,
                    autohidemode: 'scroll',
                    railpadding: { right: 0.5 }
                });
            }
        }, 100);
    }).resize();




    // ========================================
    //
    // Other code
    //
    // ========================================


    // Plugins
    // -------------------------

    // Popover
    $('[data-popup="popover"]').popover();


    // Tooltip
    $('[data-popup="tooltip"]').tooltip();

    //Other script

    /*$('.product_ref').livequery('change', function(e){
        e.preventDefault();
        e.stopPropagation();
        var stock = $('.stock').empty();
        var prd = $('.prod').empty();
        $('.alert-messages').empty();
        $('.btn_outstock').removeAttr('disabled');
        $('.product_qte').removeAttr('disabled');
        $('.stock_input').val(' ');
        $('.refprd').val(' ');
        $('.stock strong').empty();
        var elt = $(this);
        var data = elt.val();
        $.ajax({
            type : 'get',
            dataType: 'json',
            cache: false,
            url : Routing.generate('labs_facturation_stock_get_qte_rest', { id : data}),
            success : function(data){
                $.each(data, function(index, value) {
                    stock.append(value.qte);
                    prd.append(value.prd);
                    $('.stock_input').val(value.qte);
                    $('.refprd').val(value.identify);
                    if(value.qte <= 0)
                    {
                        $('.btn_outstock').attr('disabled', 'disabled');
                        $('.product_qte').attr('disabled', 'disabled');
                        $('.alert-messages').append('Ce produit est en rupture de stock, aucune sortie n\' est autorisée');
                    }
                });
            }

        });

    });*/

    $('.entrepot_ref').livequery('change', function(e){
        e.preventDefault();
        e.stopPropagation();
        var stock = $('.stock_entrepot').empty();
        $('.alert-messages').empty();
        $('.btn_outstock').removeAttr('disabled');
        $('.product_qte').removeAttr('disabled');
        $('.stock_input_entrepot').val(' ');

        //$('.stock strong').empty();
        var elt = $(this);
        var entp = elt.val();
        var prod=  $(".product_ref option:selected").val();
        $.ajax({
            type : 'get',
            dataType: 'json',
            cache: false,
            url : Routing.generate('labs_facturation_stock_get_qte_entrepot_stock', { product : prod, entrepot : entp}),
            success : function(data){
                $.each(data, function(index, value) {
                    stock.append(value.entrepot_qte);
                    $('.stock_input_entrepot').val(value.entrepot_qte);
                    if(value.entrepot_qte <= 0)
                    {
                        $('.btn_outstock').attr('disabled', 'disabled');
                        $('.product_qte').attr('disabled', 'disabled');
                        $('.alert-messages').append('Ce produit est en rupture de stock dans ce entrepot, aucune sortie n\' est autorisée');
                    }
                });
            }
        });

    });


    /*$('.product_qte').on('keyup',function(e){
        e.preventDefault();
        e.stopPropagation();
        var qte = $(this).val();
        var inputStock = $('.stock_input').val();
        var inputStockEntrepot = $('.stock_input_entrepot').val();
        qte = parseInt(qte);
        inputStock = parseInt(inputStock);
        inputStockEntrepot = parseInt(inputStockEntrepot);
        if(inputStock < qte){
            $('.alert-messages').empty().append('Désolé, vous ne pouvez pas faire une sortie de stock supérieur au stock du produit');
            $('.btn_outstock').attr('disabled', 'disabled');
        }else{
            $('.alert-messages').empty();
            $('.btn_outstock').removeAttr('disabled');
        }
        if(inputStockEntrepot < qte){
            $('.alert-messages').empty().append('Désolé, vous ne pouvez pas faire une sortie de stock supérieur au stock du produit dans ce entrepôt');
            $('.btn_outstock').attr('disabled', 'disabled');
        }else{
            $('.alert-messages').empty();
            $('.btn_outstock').removeAttr('disabled');
        }
    });*/

    /*$('.product_qte_cmd').on('keyup',function(e){
        e.preventDefault();
        e.stopPropagation();
        var qte = $(this).val();
        var inputStock = $('.stock_input').val();
        qte = parseInt(qte);
        inputStock = parseInt(inputStock);
        if(inputStock < qte){
            $('.alert-messages').empty().append('Désolé, vous ne pouvez pas faire une sortie de stock supérieur au stock du produit');
            $('.btn_outstock').attr('disabled', 'disabled');
        }else{
            $('.alert-messages').empty();
            $('.btn_outstock').removeAttr('disabled');
        }
    });*/




       // Envoi l'arrete de la facture en ajax
       $('.SendArrete').submit( function(event){
            event.preventDefault();
            var $this = $(this);
            var id = $('.input-hidden-id').val();
            var DATA = $this.serialize();
            //Ici on peut ajouter un loader...
            $.ajax({
                url: Routing.generate('labs_facturation_proforma_edit_arrete', { id : id}),
                data: DATA,
                type: 'POST',
                dataType: 'json',
                cache: false,
                success : function(response) {
                        $('.arrete-info').removeClass('displayNone').show();
                        $this.addClass('displayNone').hide();
                        $('.somme_arrete').empty().append(response.data);
                        $('.arrete').val(response.data);
                },
                error: function(response){
                    console.log(response);
                }
            });
        });

        //Affiche le formulaire de mise à jour de l'arrete de la facture
        $('.arreter-edit').on('click', function(e){
            e.preventDefault();
            $('.SendArrete').removeClass('displayNone').show();
            $('.arrete-info').addClass('displayNone').hide();
        });

    /*$('.validate-pro').on('click', function(e){
        e.preventDefault();
        e.stopPropagation();
        var $this = $(this);
        $.ajax({
            type : 'get',
            dataType: 'json',
            url : $this.attr('href'),
            success : function(data){
                $.each(data, function(index, value) {
                    $this.addClass('displayNone').hide();
                    $('.groupaction').removeClass('displayNone').show();
                });
            }
        });
    });*/

    /**
     * Traitement de la commande script sur la selection de l'entrepot
     */

     /*   $('.entrepot').livequery('change', function(e){
            e.preventDefault();
            e.stopPropagation();
            var $this = $(this);

            //Trouvez l'id du produits
            var $ids = $this.attr('id');
            var $id = $ids.split('-');
            var $id1 = $id[1];
            var $content_entrepot = $('.content_entrepot'+$id1);
            var $emplacement = $('#emplacement'+$id1);
            var $qte_entrepot_dispo = $('#qte_entrepot_dispo'+$id1);
            var $qte_total_dispo = $('#qte_total_dispo'+$id1);
            // les inputs hidden des valeur stock entrepot et stock produits
            var $qte_entrepot_dispo_input = $('#qte_entrepot_dispo_input'+$id1);
            var $qte_total_dispo_ipnut = $('#qte_total_dispo_input'+$id1);


            // les paramètres
            var $product_id = $('#product'+$id1).val();
            var $entrepot_id = $this.val();

            var $champqte = $('#qte_inventaire-'+$id1);
            var $qtecmd = $('#qtecmd'+$product_id).val();
            var $btn = $('#out'+$id1);

            //alerte message
            var $message_alerts = $('.alert-messages'+$id1);
            $message_alerts.empty();

            $champqte.removeAttr('disabled');
            $btn.removeAttr('disabled');

            $.ajax({
                type : 'get',
                dataType: 'json',
                cache: false,
                url : Routing.generate('labs_facturation_stock_get_qte_entrepot_stock', { product : $product_id, entrepot : $entrepot_id}),
                success : function(data){
                    $.each(data, function(index, value){
                        $content_entrepot.empty().append(value.name_entrepot.name);
                        $emplacement.empty().append(value.name_entrepot.emplacement);
                        $qte_entrepot_dispo.empty().append(value.entrepot_qte);
                        $qte_total_dispo.empty().append(value.product_qte);

                        $qte_entrepot_dispo_input.empty().val(value.entrepot_qte);
                        $qte_total_dispo_ipnut.empty().val(value.product_qte);

                        if($qtecmd > value.entrepot_qte)
                        {
                            $champqte.attr('disabled','disbled');
                            $btn.attr('disabled','disbled');
                            $message_alerts.empty().append('Vous ne pouvez pas effectuer cette opération, le stock disponible est inférieur à la quantité commandée');
                        }
                        if($qtecmd > value.product_qte)
                        {
                            $champqte.attr('disabled','disbled');
                            $btn.attr('disabled','disbled');
                            $message_alerts.empty().append('Vous ne pouvez pas effectuer cette opération, le stock disponible est inférieur à la quantité commandée');
                        }
                    });
                }


            });
        }); */

    /**
     * Action keyup sur le champs inventaire de stock
     */

   /* $('.qte_inventaire').livequery('keyup', function(e){
        e.preventDefault();
        e.stopPropagation();

        var $this = $(this);

        var $ids = $this.attr('id');
        var qteinventaire = $this.val();
        var $id = $ids.split('-');
        var $id1 = $id[1]
        qteinventaire = parseInt(qteinventaire);
        var $btn = $('#out'+$id1);
        //parametre id de produits
        var $Merror = $('#basic-error'+$id1);

        var $product_id = $('#product'+$id1).val();
        var $qtecmd = $('#qtecmd'+$product_id).val();

        //Qte stock entrepot
        var $qte_entrepot_dispo_input = $('#qte_entrepot_dispo_input'+$id1);
        var $qteEntrepot = $qte_entrepot_dispo_input.val();

        $btn.removeAttr('disabled');
        $this.removeClass('bg-danger');
        $Merror.empty();

        if(!parseInt(qteinventaire)){
            $Merror.empty().append('Entrez une valeur numérique');
            $btn.attr('disabled','disbled');
            $this.addClass('bg-danger');
        }

        if(qteinventaire > $qtecmd){
            $Merror.empty().append('Supérieur à la qté commandée');
            $btn.attr('disabled','disbled');
            $this.addClass('bg-danger');
        }
    }); */

    /**
     * Fonction ajax send data in stock inventor
     */

        // Envoi l'arrete de la facture en ajax
   /* $('.formCmd').livequery('submit',function(event){
        event.preventDefault();
        var $this = $(this);
        var $ids = $this.attr('id');
        var $id = $ids.split('-');
        var $id1 = $id[1]
        var $product_id = $('#product'+$id1).val(); // id du produit
        var $qteCmd_content = $('#qtecmd_content'+$product_id); // identification de la class, content de la qte cmd  du produit
        var $qteCmd = $('#qtecmd'+$product_id); // identification de champs caché de la qté cmd du produit
        var $labsqteCmd = $('#labsqtecmd'+$product_id); // identification de champs caché de la qté cmd du produit
        var DATA = $this.serialize(); // serialisation des données du formaulaire
        //Ici on peut ajouter un loader...
        var $btn = $('#out'+$id1); // bouton d'ajout du formualire
        $btn.attr('disabled','disbled');
        var $qte_entrepot_dispo_input = $('#qte_entrepot_dispo_input'+$id1);
        var $qte_total_dispo_ipnut = $('#qte_total_dispo_input'+$id1);
        var $qte_entrepot_dispo = $('#qte_entrepot_dispo'+$id1);
        var $qte_total_dispo = $('#qte_total_dispo'+$id1);

        var options = {
            url: Routing.generate('labs_facturation_stock_get_qte_inventor_cmd', { product : $product_id}),
            type: 'POST',
            dataType: 'json',
            success : function(response) {
                $.each(response, function(index, value){

                    var $qte_ent_dispo = ($qte_entrepot_dispo_input .val() - value.qtecommande);
                    var $qte_tt_dispo = ($qte_total_dispo_ipnut.val() - value.qtecommande);

                    $qte_entrepot_dispo.empty().append($qte_ent_dispo);
                    $qte_total_dispo.empty().append($qte_tt_dispo);

                    $qte_entrepot_dispo_input.empty().val($qte_ent_dispo);
                    $qte_total_dispo_ipnut.empty().val($qte_tt_dispo);

                    $btn.removeAttr('disabled');
                    $qteCmd_content.empty().append(value.newQte);
                    $qteCmd.empty().val(value.newQte);
                    $labsqteCmd.empty().val(value.newQte);
                    $('#basic-message').empty().append(value.succes);
                });
            },
            clearFields : true,
            resetForm : true,
            clearForm : true
        }
        $this.ajaxSubmit(options);
    });*/
});



