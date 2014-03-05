<?php $this->titre = "Réinitilisation de votre mot de passe" ?>

<form id="formulaire_reset_mdp" action="<?= ABSOLUTE_ROOT . "/controllers/ControllerUser.php?action=modifierMDPPerdu&hash_validation=" . $_GET["hash_validation"] . "&email=" . $_GET['email'] ?>" method="post">
    <p>
        <label for="mdp"> Nouveau mot de passe: </label>
        <input type="password" name="mdp" id="mdp" value="" />
    </p>
    
    <p>
        <label for="mdp_verif"> Nouveau mot de passe (vérification): </label>
        <input type="password" name="mdp_verif" id="mdp_verif" value="" />
    </p>
    
    <input type="hidden" name="email" value="<?= $user->getEmail() ?>" />
    <input type="hidden" name="hash_validation" value="<?= $user->getHash_validation() ?>" />
           
    <p>
            <input type="button" name="envoiNouveauMDPPerdu" value="Valider" />
    </p>
</form>

<script>

$( "#formulaire_reset_mdp input[name=envoiNouveauMDPPerdu]" ).click(function() {
        var email = $("#formulaire_reset_mdp input[name=email]").val(); 
	var password = $("#formulaire_reset_mdp input[name=mdp]").val();
	var password_verif = $("#formulaire_reset_mdp input[name=mdp_verif]").val();

	if( !isValidPassword(password) ){
		$("#erreur ul").append("<li> Votre mot de passe doit comporter entre 6 et 15 caractères </li>");
	}
	else if( password == password_verif ) {
		var hash = CryptoJS.SHA1(email + password + email);
		$("#formulaire_reset_mdp input[name=mdp]").val(hash);
		$("#formulaire_reset_mdp input[name=mdp_verif]").val(hash);
		$( "#formulaire_reset_mdp" ).submit();
	}
});

</script>