<?php $this->titre = "Connexion";

//Inclusion de la libarairie Form ( http://fr.openclassrooms.com/informatique/cours/votre-site-php-presque-complet-architecture-mvc-et-bonnes-pratiques/gestion-des-formulaires-avec-la-classe-form )
include ROOT.'/models/Form.php';

// "formulaire_connexion" est l'ID unique du formulaire
$form_connexion = new Form('formulaire_connexion', 'POST');

$form_connexion->action(ABSOLUTE_ROOT . '/controllers/ControllerUser.php?action=connexion');

$form_connexion->add('Email', 'email')
                 ->label("Votre adresse email")->Required(true); 

$form_connexion->add('Password', 'mdp')
                 ->label("Votre mot de passe");

$form_connexion->add('Button', 'submitConnexion')
                 ->value("Connexion");

// Pré-remplissage avec les valeurs précédemment entrées (s'il y en a)
$form_connexion->bound($_POST);

echo $form_connexion;

?>
<p id="mdpPerdu">
	<a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerUser.php?action=afficherMDPPerdu' ?>">Mot de passe oublié?</a>
</p>
<script>

$( "#formulaire_connexion input[name=submitConnexion]" ).click(function() {
	var email = $("#formulaire_connexion input[name=email]").val(); 
	var password = $("#formulaire_connexion input[name=mdp]").val();

	if( !isValidPassword(password) ){
		$("#erreur ul").append("<li> Votre mot de passe doit comporter entre 6 et 15 caractères </li>");
	}
	else{
		var hash = CryptoJS.SHA1(email + password + email);
		$("#formulaire_connexion input[name=mdp]").val(hash);
		$( "#formulaire_connexion" ).submit();
	}
});

</script>
