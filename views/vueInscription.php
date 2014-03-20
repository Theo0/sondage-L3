<?php
$this->titre = "Inscription";

//Inclusion de la libarairie Form ( http://fr.openclassrooms.com/informatique/cours/votre-site-php-presque-complet-architecture-mvc-et-bonnes-pratiques/gestion-des-formulaires-avec-la-classe-form )
include ROOT.'/models/Form.php';

// "formulaire_inscription" est l'ID unique du formulaire
$form_inscription = new Form('formulaire_inscription', 'POST');

$form_inscription->action(ABSOLUTE_ROOT . '/controllers/ControllerUser.php?action=inscription');

$form_inscription->add('Text', 'pseudo')
                 ->label("Pseudo")->Required(true);
		 
$form_inscription->add('Text', 'prenom')
                 ->label("Prénom")->Required(true);
		 
$form_inscription->add('Text', 'nom')
                 ->label("Nom de famille")->Required(true);
		 
$form_inscription->add('Email', 'email')
                 ->label("Votre adresse email")->Required(true); 

$form_inscription->add('Password', 'mdp')
                 ->label("Votre mot de passe");

$form_inscription->add('Password', 'mdp_verif')
                 ->label("Votre mot de passe (vérification)");

$form_inscription->add('Button', 'submitInscription')
                 ->value("Créer un compte");

// Pré-remplissage avec les valeurs précédemment entrées (s'il y en a)
$form_inscription->bound($_POST);

echo $form_inscription;

?>

<script>

$( "#formulaire_inscription input[name=submitInscription]" ).click(function() {
	var email = $("#formulaire_inscription input[name=email]").val(); 
	var password = $("#formulaire_inscription input[name=mdp]").val();
	var password_verif = $("#formulaire_inscription input[name=mdp_verif]").val();
	
	$("#erreur ul").text("");

	if( !isValidPassword(password) ){
		$("#erreur ul").append("<li> Votre mot de passe doit comporter entre 6 et 15 caractères </li>");
	} else if (password != password_verif) {
	    $("#erreur ul").append("<li> Votre mot de passe de vérification n'est pas identique à votre mot de passe </li>");
	}
	else if( password == password_verif ) {
		var hash = CryptoJS.SHA1(email + password + email);
		$("#formulaire_inscription input[name=mdp]").val(hash);
		$("#formulaire_inscription input[name=mdp_verif]").val(hash);
		$( "#formulaire_inscription" ).submit();
		$("#formulaire_inscription input[name=mdp_verif]").val(password);
	}
});

</script>
