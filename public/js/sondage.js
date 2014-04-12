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
                $("#listeCommentaires").append('<li class="commentaire" id="commentaire' + $.trim(data) + '"> \
                                                    <p class="pseudoCommentaire">' + $("#pseudo").text() + '</p>\
                                                     <span class="texteCommentaire">'  + commentaire + '</span>\
                                                     <span class="blocSoutiens">\
                                                         <span id="soutien' + $.trim(data) + '" >0</span>\
                                                         <span> <img src="' + ABSOLUTE_ROOT  + '/public/img/facebook-like-icon.png" onclick="ajouterSoutien(' + $.trim(data)  + ')" /> </span>\
                                                     </span>\
                                                     <ul class="listeSousCommentaires" id="listeSousCommentaires' + $.trim(data) + '"></ul>\
                                                     <textarea class="textareaSousCommentaire" id="' + data + '" name="ajouterCommentaire" placeholder="Ecrire un sous commentaire..."></textarea> \
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
                $("#commentaire" + idCommentaire + " ul").append('<li class="sousCommentaire" id="sousCommentaire' + $.trim(data) + '"> <span class="pseudoCommentaire">' + $("#pseudo").text() + '</span> - ' + commentaire + '</li>');
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
                    ajouterModerateurSondage(ui.item.id, $("#idGroupe").val(), ui.item.value);
            });
            
          return false;
        }
      });
      });    
     
}

