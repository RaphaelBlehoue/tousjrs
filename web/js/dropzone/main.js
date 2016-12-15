$(document).ready(function(){
    //je récupère l'action où sera traité l'upload en PHP
    var _actionToDropZone = $("#dropzone-upload").attr('action');

    //je définis ma zone de drop grâce à l'ID de ma div citée plus haut.
    Dropzone.autoDiscover = false;
    var myDropzone = new Dropzone("#dropzone-upload", {
        url: _actionToDropZone,
        dictDefaultMessage: 'Faites glisser des fichiers ici <span>ou Sélectionnez des fichiers sur l\'ordinateur</span>',
        maxFilesize: 2, // MB
        maxFiles: 30,
        maxThumbnailFilesize: 1,
        addRemoveLinks: true,
        acceptedFiles : "image/jpeg,image/png,image/gif",
        init : function(){
            this.on('addedfile', function(file){
                console.log("Added file." + file);
                console.log(_actionToDropZone);
            });
            this.on('complete', function(file){
                console.log('super '+file);
                //$('#photoView').removeClass('photoView').show();
            });

            this.on('maxfilesexceeded', function(){
                console.log("Limite de fichier simultannée attient");
            });
        }
    });

});