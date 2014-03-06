
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