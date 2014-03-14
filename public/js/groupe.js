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

function afficherDialogueAjoutMembreGroupe() {
       
    //Si le div du dialogue existe on l'affiche
    if ($("#dialogAjoutMembreGroupe").length>0) {
        //Affichage du dialogue
        $(function() {
            $( "#dialogAjoutMembreGroupe" ).dialog({
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
    $('#boutonFermerAjoutMembreGroupe').click(function () {
        $( "#dialogAjoutMembreGroupe" ).dialog("close");
    });
    
    
    /* Autocomplétion pour ajouter un membre */
     $(function() {
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
 
    $( "#nomMembre" )
      // don't navigate away from the field on tab when selecting an item
      .bind( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).data( "ui-autocomplete" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        source: function( request, response ) {
          $.getJSON( ABSOLUTE_ROOT + '/index.php', {
            params: extractLast( request.term ) + ',' + $("#idGroupe").val(),
            controller: "User",
            action: "ajaxGetMembresLike"
          }, response );
        },
        search: function() {
          // custom minLength
          var term = extractLast( this.value );
          if ( term.length < 2 ) {
            return false;
          }
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          this.value = ui.item.value;
          
          //Listener clic pour bouton ajouter un membre au groupe
            $('#lienAjoutMembre').click(function(){
                    ajouterMembreGroupe(ui.item.id, $("#idGroupe").val(), ui.item.value);
            });
            
          return false;
        }
      });
      });    
     
}

function ajouterMembreGroupe(idUser, idGroupe, nomUser){
    //Retourne vrai si le membre a été ajouté au groupe
    $.get( ABSOLUTE_ROOT + "/index.php?controller=Groupe&action=ajaxAjouterMembreGroupe&params=" + idUser + "," + idGroupe, function( addUserToGroupe ) {
        if (addUserToGroupe==1) {
            $(".user" + idUser).hide();
            
            //Si la liste des membres en attente est vide on affiche qu'il n'y a plus de membres attente sinon on cache le message
            if ($('.listeMembresEnAttente li').length == 2)
                $(".aucunMembreEnAttente").show();
            else
                $(".aucunMembreEnAttente").hide();
                
            $(".listeMembres").append('<li class="user' + idUser + '">' + nomUser + '</li>');
            if ($("#canDelete").length != 0) {
                $(".user" + idUser).append('<span class="lienSupprimerMembre"><a href="#" id="supprimerMembre' + idUser + '" onclick="supprimerMembreGroupe(' + idUser + ', ' + idGroupe + ')"> supprimer </a></span>')
            }
            $(".aucunMembre").hide();
        }
        else{
            $("#dialogErreur").text("Impossible d'ajouter le membre au groupe");
        }
    });    
}

function supprimerMembreGroupe(idUser, idGroupe){
    //Retourne vrai si le membre a été ajouté au groupe
    $.get( ABSOLUTE_ROOT + "/index.php?controller=Groupe&action=ajaxSupprimerMembreGroupe&params=" + idUser + "," + idGroupe, function( rmUserToGroupe ) {
        if (rmUserToGroupe==1) {
            $(".user" + idUser).remove();
            
            //Si la liste des membres est vide on affiche qu'il n'y a plus de membres sinon on cache le message
            if ($('.listeMembres li').length == 2)
                $(".aucunMembre").show();
            else
                $(".aucunMembre").hide();
        }
        else{
            $("#dialogErreur").text("Impossible de supprimer le membre au groupe");
        }
    });    
}


function afficherDialogueAjoutModerateurGroupe() {
       
    //Si le div du dialogue existe on l'affiche
    if ($("#dialogAjoutModerateurGroupe").length>0) {
        //Affichage du dialogue
        $(function() {
            $( "#dialogAjoutModerateurGroupe" ).dialog({
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
    $('#boutonFermerAjoutModerateurGroupe').click(function () {
        $( "#dialogAjoutModerateurGroupe" ).dialog("close");
    });
    
    
    /* Autocomplétion pour ajouter un membre */
     $(function() {
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
 
    $( "#nomModerateur" )
      // don't navigate away from the field on tab when selecting an item
      .bind( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).data( "ui-autocomplete" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        source: function( request, response ) {
          $.getJSON( ABSOLUTE_ROOT + '/index.php', {
            params: extractLast( request.term ) + ',' + $("#idGroupe").val(),
            controller: "User",
            action: "ajaxGetMembresLike"
          }, response );
        },
        search: function() {
          // custom minLength
          var term = extractLast( this.value );
          if ( term.length < 2 ) {
            return false;
          }
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          this.value = ui.item.value;
          
          //Listener clic pour bouton ajouter un membre au groupe
            $('#lienAjoutModerateur').click(function(){
                    ajouterModerateurGroupe(ui.item.id, $("#idGroupe").val(), ui.item.value);
            });
            
          return false;
        }
      });
      });    
     
}

function ajouterModerateurGroupe(idUser, idGroupe, nomUser){
    //Retourne vrai si le membre a été ajouté au groupe
    $.get( ABSOLUTE_ROOT + "/index.php?controller=Groupe&action=ajaxAjouterModerateurGroupe&params=" + idUser + "," + idGroupe, function( addUserToGroupe ) {
        if (addUserToGroupe==1) { 
            $(".listeModerateurs").append('<li class="user' + idUser + '">' + nomUser + '</li>');
            if ($("#canDelete").length != 0) {
                $(".user" + idUser).append('<span class="lienSupprimerModerateur"><a href="#" id="supprimerModerateur' + idUser + '" onclick="supprimerModerateurGroupe(' + idUser + ', ' + idGroupe + ')"> supprimer </a></span>')
            }
            $(".aucunModerateur").hide();
        }
        else{
            $("#dialogErreur").text("Impossible d'ajouter le moderateur au groupe");
        }
    });    
}

function supprimerModerateurGroupe(idUser, idGroupe){
    //Retourne vrai si le modérateur a été ajouté au groupe
    $.get( ABSOLUTE_ROOT + "/index.php?controller=Groupe&action=ajaxSupprimerModerateurGroupe&params=" + idUser + "," + idGroupe, function( rmUserToGroupe ) {
        if (rmUserToGroupe==1) {
            //Suppression du modérateur de la liste des modérateurs
            $(".user" + idUser).remove(); 

            //Si la liste des modérateurs est vide on affiche qu'il n'y a plus de modérateurs sinon on cache le message
            if ($('.listeModerateurs li').length == 2)
                $(".aucunModerateur").show();
            else
                $(".aucunModerateur").hide();
        }
        else{
            $("#dialogErreur").text("Impossible de supprimer le membre au groupe");
        }
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