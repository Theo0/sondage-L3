var ABSOLUTE_ROOT = 'http://localhost/sondage-L3';

function afficherDialogueCreationGroupe() {
       
    //Si le div du dialogue existe on l'affiche
    if ($("#dialogCreationGroupe").length>0) {
        //Affichage du dialogue
        $(function() {
            $( "#dialogCreationGroupe" ).dialog({
                closeOnEscape: false,
                draggable: false,
                height: 450,
                width: 450,
                modal: true
            });
            $(".ui-dialog-titlebar-close").hide(); //On cache le bouton pour fermer la fenêtre
        });
    }
    //Bouton de fermeture de dialog
    $('#boutonAnnulerGroupe').click(function () {
        $( "#dialogCreationGroupe" ).dialog("close");
    });
}

function creerGroupe(nomGroupe, visibilite){
    if (nomGroupe.length==0) {
        $("#dialogErreur").text("Le nom du groupe ne peut pas être vide");
    } else{
        //Retourne vrai si le groupe avec un même nom n'existe pas
        $.get( ABSOLUTE_ROOT + "/index.php?controller=Groupe&action=ajaxIsUniqueNomGroupe&params=" + nomGroupe, function( uniqueNomGroupe ) {
            if (uniqueNomGroupe==1) { 
                $("#formCreationGroupe").submit();
            }
            else{
                $("#dialogErreur").text("Le nom de ce groupe n'est pas disponible");
            }
        });
    }
}