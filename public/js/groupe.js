var ABSOLUTE_ROOT = 'http://localhost/sondage-L3';

$( document ).ready(function() {
    $.getJSON( ABSOLUTE_ROOT + "/index.php?controller=Groupe&action=ajaxGetListeGroupes", function( listeGroupes ) {       
        $.each( listeGroupes, function( key, val ) {
            $("#listeGroupes").prepend( '<li> \
                                      <img id="groupEditImg' + val.id + '" class="editGroup" src="' + ABSOLUTE_ROOT + '/public/css/images/settings-icon.png" onclick="displayEditMenu(' + val.id + ', this)" alt="0"/> \
                                      <ul class="menuEditGroup" id="menuEditGroup' + val.id +'"> <li><a href="' + ABSOLUTE_ROOT + '/index.php?controller=Groupe&action=quitterGroupe&params=' + val.id + '"> Quitter le groupe </a></li></ul>\
                                      <a href="' + ABSOLUTE_ROOT + '/index.php?controller=Groupe&action=afficherGroupe&params=' + val.id + '" > \
                                       '+ val.nom + '</a></li>' );
        });        
    });
    

});

function displayEditMenu(idGroup, button){
    
    if ($("#groupEditImg" + idGroup).attr("alt") == "0" || $("#groupEditImg" + idGroup).attr("alt") == "" ) { 
        $(".menuEditGroup").hide();
        $("#menuEditGroup" + idGroup).show();
        $("#groupEditImg" + idGroup).css('border', 'solid 1px #b3696c');
        $("#groupEditImg" + idGroup).attr("alt", "1");

    } else{ 
        $("#menuEditGroup" + idGroup).hide()
        $("#groupEditImg" + idGroup).css('border', 'none');
        $("#groupEditImg" + idGroup).attr("alt", '0');
    }
    
    //Mouse click on sub menu
    $("#menuEditGroup" + idGroup).mouseup(function()
    {
        console.log(idGroup);
        return false
    });
    
    //Document Click
    $(document).mouseup(function()
    {
        $(".menuEditGroup").hide();
        $("#groupEditImg" + idGroup).removeAttr('style');
        $("#groupEditImg" + idGroup).attr("alt", '');
    });


    /*
        button.click(function() {
            // Make use of the general purpose show and position operations
            // open and place the menu where we want.
            
                menu.show().position({
                    my: "left top",
                    at: "left bottom",
                    of: this
              });              
            }
            else{
                menu.hide();
            }

     
            // Make sure to return false here or the click registration
            // above gets invoked.
            return false;
       });
    var firstClick = false;
 
*/
}

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