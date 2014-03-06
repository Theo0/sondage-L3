<?php $this->titre = "Groupe: erreur" ?>

<script>
    $( document ).ready(function() {
        $("#dialogErreur").text("<?= $erreur ?>");
        afficherDialogueCreationGroupe();
        console.log( "ready!" );
    });
    
</script>