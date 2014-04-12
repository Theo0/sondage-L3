$(document).ready(function(){
    $("#textareaCommentaire").keydown(function(e){
        if (e.keyCode == 13) { 
            ajouterCommentaire($("#textareaCommentaire").val(), $("#idSondage").val());
            return false;
        }
        return true;
    });
    
    $(".textareaSousCommentaire").keydown(function(e){
        if (e.keyCode == 13) { 
            ajouterSousCommentaire($(this).val(), $(this).attr("id"));
            
            return false;
        }
        return true;
    }); 
})

function ajouterCommentaire(commentaire, idSondage){
    $.post( ABSOLUTE_ROOT + "/index.php?controller=Sondage&action=ajaxAjouterCommentaire", { texteCommentaire: commentaire, sondageId: idSondage })
        .done(function( data ) {
            if ($.isNumeric(data)) {
                var lienSuppressionCom = '';
                if ($("#canDelete").length != 0) {
                    lienSuppressionCom = '<span><a href="#" onclick="supprimerCommentaire(' + $.trim(data) + ')"><img src="http://localhost/sondage-L3/public/css/images/red-cross.png"> </a></span>';
                }
                $("#listeCommentaires").append('<li class="commentaire" id="commentaire' + $.trim(data) + '"> \
                                                    <p class="pseudoCommentaire">' + $("#pseudo").text() + '</p>\
                                                     <span class="texteCommentaire">'  + commentaire + '</span>\
                                                     <span class="blocSoutiens">\
                                                         <span id="soutien' + $.trim(data) + '" >0</span>\
                                                         <span> <img src="' + ABSOLUTE_ROOT  + '/public/img/facebook-like-icon.png" onclick="ajouterSoutien(' + $.trim(data)  + ')" /> </span>\
                                                     </span>' + lienSuppressionCom + '\
                                                     <ul class="listeSousCommentaires" id="listeSousCommentaires' + $.trim(data) + '"></ul>\
                                                     <textarea class="textareaSousCommentaire" id="' + $.trim(data) + '" name="ajouterCommentaire" placeholder="Ecrire un sous commentaire..."></textarea> \
                                                 </li>');
                $("#textareaCommentaire").val("");
                $("#erreur ul").html("");
            } else{
                $("#erreur ul").html("");
                $("#erreur ul").append("<li class=\"errorEntry\">" + data + "</li>");
            }
            
        })
        .fail(function() {
            alert( "error" );
        });
}

function ajouterSousCommentaire(commentaire, idCommentaire){
    $.post( ABSOLUTE_ROOT + "/index.php?controller=Sondage&action=ajaxAjouterSousCommentaire", { texteCommentaire: commentaire, commentaireId: idCommentaire })
        .done(function( data ) {
            if ($.isNumeric(data)) {
                var lienSuppressionCom = '';
                if ($("#canDelete").length != 0) {
                    lienSuppressionCom = '<span><a href="#" onclick="supprimerCommentaire(' + $.trim(data) + ')"><img src="http://localhost/sondage-L3/public/css/images/red-cross.png"> </a></span>';
                }
                $("#commentaire" + idCommentaire + " ul").append('<li class="sousCommentaire" id="sousCommentaire' + $.trim(data) + '"> <span class="pseudoCommentaire">' + $("#pseudo").text() + '</span> - ' + commentaire + lienSuppressionCom +'</li>');
                $(".textareaSousCommentaire").val("");
                $("#erreur ul").html("");
            } else{
                $("#erreur ul").html("");
                $("#erreur ul").append("<li class=\"errorEntry\">" + data + "</li>");
            }
            
        })
        .fail(function() {
            alert( "error" );
        });
}




function ajouterSoutien(idCommentaire) {
    $.post( ABSOLUTE_ROOT + "/index.php?controller=Sondage&action=ajaxAjouterSoutienCommentaire", { idCom: idCommentaire})
        .done(function( data ) {
            if (data >= 1) {
                $("#soutien" + idCommentaire).html( parseInt($("#soutien" + idCommentaire).text()) + 1);
            }            
        })
        .fail(function() {
            alert( "error" );
        });
}


function supprimerCommentaire(idCommentaire){
    $.post( ABSOLUTE_ROOT + "/index.php?controller=Sondage&action=ajaxSupprimerCommentaire", { commentaireId: idCommentaire })
        .done(function( rm ) {
            if (rm == 1) {
                $("#commentaire" + idCommentaire).remove();
                $("#sousCommentaire" + idCommentaire).remove();
                $("#erreur ul").html("");
            } else{
                $("#erreur ul").html("");
                $("#erreur ul").append("<li class=\"errorEntry\">" + rm + "</li>");
            }
            
        })
        .fail(function() {
            alert( "error" );
        });
}



function afficherDialogueAjoutModerateurSondage() {
       
    //Si le div du dialogue existe on l'affiche
    if ($("#dialogAjoutModerateurSondage").length>0) {
        //Affichage du dialogue
        $(function() {
            $( "#dialogAjoutModerateurSondage" ).dialog({
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
    $('#boutonFermerAjoutModerateurSondage').click(function () {
        $( "#dialogAjoutModerateurSondage" ).dialog("close");
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
            params: extractLast( request.term ) + ',' + $("#idSondage").val(),
            controller: "User",
            action: "ajaxGetMembresLikeSondage"
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
                    ajouterModerateurSondage(ui.item.id, $("#idSondage").val(), ui.item.value);
            });
            
          return false;
        }
      });
      });    
     
}


function ajouterModerateurSondage(idUser, idSondage, nomUser){
    //Retourne vrai si le membre a été ajouté au groupe
    $.get( ABSOLUTE_ROOT + "/index.php?controller=Sondage&action=ajaxAjouterModerateurSondage&params=" + idUser + "," + idSondage, function( addUserToSondage ) {
        if (addUserToSondage==1) { 
            $(".listeModerateurs").append('<li class="user' + idUser + '">' + nomUser + '</li>');
            if ($("#canDelete").length != 0) {
                $(".user" + idUser).append('<span class="lienSupprimerModerateur"><a href="#" id="supprimerModerateur' + idUser + '" onclick="supprimerModerateurSondage(' + idUser + ', ' + idSondage + ')"> supprimer </a></span>')
            }
            $(".aucunModerateur").hide();
        }
        else{
            $("#dialogErreur").text("Impossible d'ajouter le moderateur au groupe");
        }
    });    
}

function supprimerModerateurSondage(idUser, idSondage){
    //Retourne vrai si le modérateur a été ajouté au groupe
    $.get( ABSOLUTE_ROOT + "/index.php?controller=Sondage&action=ajaxSupprimerModerateurSondage&params=" + idUser + "," + idSondage, function( rmUserToSondage ) {
        if (rmUserToSondage==1) {
            //Suppression du modérateur de la liste des modérateurs
            $(".user" + idUser).remove(); 

            //Si la liste des modérateurs est vide on affiche qu'il n'y a plus de modérateurs sinon on cache le message
            if ($('.listeModerateurs li').length == 2)
                $(".aucunModerateur").show();
            else
                $(".aucunModerateur").hide();
        }
        else{
            $("#dialogErreur").text("Impossible de supprimer le modérateur du sondage");
        }
    });    
}

