$(document).ready(function(){
    //je récupère l'action où sera traité l'upload en PHP
    var _that = $("#dropzone-upload");
    var _actionToDropZone = _that.attr('action');
    var _entity = _that.data('name');

    //je définis ma zone de drop grâce à l'ID de ma div citée plus haut.
    Dropzone.autoDiscover = false;
    var myDropzone = new Dropzone("#dropzone-upload", {
        url: _actionToDropZone,
        dictDefaultMessage: 'Faites glisser des fichiers ici <span>ou Sélectionnez des fichiers sur l\'ordinateur</span>',
        maxFilesize: 2, // MB
        maxFiles: 30,
        maxThumbnailFilesize: 1,
        addRemoveLinks: false,
        acceptedFiles : "image/jpeg,image/png,image/gif",
        init : function(){
            this.on('addedfile', function(file){
                
            });
            this.on('success', function(file, responseText, e){
                var defaultButton = Dropzone.createElement('<div class="default_pic_container"><a id="'+responseText.media+'" class="btn btn-success btn-labeled link"><b><i class="icon-pushpin"></i></b>Mettre en avant</a></div>');
                file.previewElement.appendChild(defaultButton);
                console.log(_entity);
                defaultButton.addEventListener('click', function (evt) {
                    evt.preventDefault();
                    evt.stopPropagation();
                    addStatus(responseText.media, _entity, '.default_pic_container');
                })
            });

            this.on('maxfilesexceeded', function(){
                console.log("Limite de fichier simultannée attient");
            });
        }
    });

    function inArray(needle, haystack) {
        var length = haystack.length;
        for(var i = 0; i < length; i++) {
            if(haystack[i] == needle) return true;
        }
        return false;
    }

    function addStatus(media, entity,elt) {
        $.ajax({
            url: Routing.generate('set_media_status', { id: media, name: entity}),
            cache: false,
            dataType: 'Json',
            method: 'GET',
            success: function (data, textStatus) {
                if (data.response_media === media){
                    var _this = $('a#'+data.response_media)
                    // Les autres buttonn
                    $(elt+' a').css('display','none').hide();
                    //Element this
                    _this.removeClass('btn-success').addClass(data.className);
                    _this.html('<b><i class="icon-sun3"></i></b>'+data.text_href+'').css('display', 'block');
                    _this.on('click', function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        if ( _this.hasClass('actived')){
                            $(elt+' a').css('display','block').show();
                            _this.removeClass(data.className).addClass('btn-success');
                            _this.empty().html('<b><i class="icon-pushpin"></i></b>Mettre en avant');
                        }
                    });
                }
            }
        });
    }
});