$(document).ready(function(){
    $("#textareaCommentaire").keydown(function(e){
        if (e.keyCode == 13) { 
            ajouterCommentaire($("#textareaCommentaire").val(), $("#idSondage").val());
            return false;
        }
        return true;
    });
    
    $("#textareaCommentaire").keydown(function(e){
        if (e.keyCode == 13) { 
            ajouterCommentaire($("#textareaCommentaire").val(), $("#idSondage").val());
            return false;
        }
        return true;
    });   
})

function ajouterCommentaire(commentaire, idSondage){
    $.post( ABSOLUTE_ROOT + "/index.php?controller=Sondage&action=ajaxAjouterCommentaire", { texteCommentaire: commentaire, sondageId: idSondage })
        .done(function( data ) {
            if (data == 1) {
                $("#listeCommentaires").prepend("<li>" + commentaire + "</li>");
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

function ajouterSoutien(idCommentaire) {
    $.post( ABSOLUTE_ROOT + "/index.php?controller=Sondage&action=ajaxAjouterSoutienCommentaire", { idCom: idCommentaire})
        .done(function( data ) {
            if (data == 1) {
                $("#soutien" + idCommentaire).html( parseInt($("#soutien" + idCommentaire).text()) + 1);
            }            
        })
        .fail(function() {
            alert( "error" );
        });
}
