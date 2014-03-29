$(document).ready(function(){
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
