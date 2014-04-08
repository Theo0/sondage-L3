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
                                                     <ul id="listeSousCommentaires"></ul>\
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
                $("#listeSousCommentaires").append('<li class="sousCommentaire" id="sousCommentaire' + $.trim(data) + '"> <span class="pseudoCommentaire">' + $("#pseudo").text() + '</span> - ' + commentaire + '</li>');
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
