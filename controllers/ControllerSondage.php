<?php

require_once "Controller.php";
require_once ROOT . "/models/Sondage.php";
require_once ROOT . "/models/Option.php";
require_once ROOT . "/models/ListeSondage.php";
require_once ROOT . "/models/ListeOption.php";
require_once ROOT . "/models/Mail.php";

class ControllerUser extends Controller{
	
	private $sondage;
	private $option;

	public function __construct() {
		parent::__construct();

		//Création du model utilisateur pour communiquer avec la base de données
		$this->sondage = new Sondage();
		$this->option = new Option();
	}

	/* Affichage de la page de creation d'un nouveau sondage */
	public function afficherNouveauSondage() {
		$this->vue = new Vue("NouveauSondage");

		//Si le contrôlleur possède des erreurs de référencées
		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue

		$this->vue->generer(array());
	}

	/* Affichage de la page quand la creation a terminé avec succès */
	public function afficherNouveauSondageTermine(){
		$this->vue = new Vue("NouveauSondageTermine");

		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);

		$this->vue->generer(array());
	}
	


	/* Création d'un sondage avec les informations reçues de $_POST: titre, description, visibilite, id de l'admin, date de fin, secret, et id du groupe */
	public function nouveauSondage(){
		//Récupération des champs du formulaire et insertion dans le modèle
		$this->sondage->POSTToVar($_POST);

		
		/* Vérification de la validité nom d'utilisateur */ 
		$validateVisibilite = $this->sondage->validateVisibilite();
		if( $validateVisibilite !== 1 ){
			$this->addErreur($validateVisibilite);
		}
	
		/* Vérification de la validité de l'adresse email */
		$validateSecret = $this->sondage->validateSecret();
		if($validateSecret !==1 ){
			$this->addErreur($validateSecret);
		}



		//Si au moins un des champs n'est pas valide
		if( !empty($this->erreurs) ){
			if(isset($_POST["redirect"])){//Si le formulaire de creation provient d'une autre page que la page de creation
				//Redirection vers la page contenant le formulaire avec envoi des erreurs
				header("Location:" . $_POST["redirect"] . "&erreurs=" . serialize($this->erreurs));
			} else{
				$this->afficherNouveauSondage(); // On réaffiche le formulaire de creation avec les erreurs
			}

		// Ajout de l'utilisateur en base
		} else{
			

			// Ajout du membre en base et récupération de l'identifiant (ou du message d'erreur)
			$id_sondage = $this->sondage->add();
			

			// Si la base de données a bien voulu ajouter l'utliisateur (pas de doublons)
			if (ctype_digit($id_sondage)) {
				
			$this->afficherNouveauSondageTermine();
			// Gestion des doublons
			} else {

				// Changement de nom de variable (plus lisible)
				$erreur =& $id_sondage;
	
				// On vérifie que l'erreur concerne bien un doublon
				if (23000 == $erreur[0]) { // Le code d'erreur 23000 siginife "doublon" dans le standard ANSI SQL
		

					$this->addErreur("Erreur ajout SQL");
					
	
				} else {
					//Ajout du message d'erreur SQL
					$this->addErreur(sprintf("Erreur ajout SQL (SQLSTATE = %d).", $erreur[0]));
				}
	
				if(isset($_POST["redirect"])){//Si le formulaire d'inscription provient d'une autre page que la page d'inscription
					//Redirection vers la page contenant le formulaire avec envoi des erreurs
					header("Location:" . $_POST["redirect"] . "&erreurs=" . serialize($this->erreurs));
				} else{
					// On reaffiche le formulaire d'inscription avec l'erreur de doublon
					$this->afficherNouveauSondage();
				}
			}			
		}
			
}

/* Affichage des sondages administrés par l'user connecté */
public function afficherSondagesAdmin(){
		$this->vue = new Vue("ListeSondage");

		//Si le contrôlleur possède des erreurs de référencées
		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue

		$ListeSondage = new ListeSondage($_SESSION['id']);
		
		$this->vue->generer(array("ListeSondage" => $ListeSondage->getArraySondage()));		
	}


public function afficherFicheSondage(){
	$this->vue = new Vue("Sondage");
	//Si le contrôlleur possède des erreurs de référencées
		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue

	$sondage = new Sondage($_GET['params']);

	$ListeOption = new ListeOption($_GET['params']);

	$this->vue->generer(array("FicheSondage" => $sondage, "ListeOptions" => $ListeOption->getArrayOption()));

}



	/* Affichage de la page de creation d'une nouvelle option */
	public function afficherNouvelleOption() {
		$this->vue = new Vue("NouvelleOption");

		//Si le contrôlleur possède des erreurs de référencées
		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue

		$ListeSondage = new ListeSondage($_SESSION['id']);
		
		$this->vue->generer(array("ListeSondage" => $ListeSondage->getArraySondage()));	
	}

	/* Affichage de la page quand la creation a terminé avec succès */
	public function afficherNouvelleOptionTermine(){
		$this->vue = new Vue("NouvelleOptionTermine");

		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);

		$this->vue->generer(array());
	}
	


	/* Création d'un sondage avec les informations reçues de $_POST: titre, description, visibilite, id de l'admin, date de fin, secret, et id du groupe */
	public function NouvelleOption(){
		//Récupération des champs du formulaire et insertion dans le modèle
		$this->option->POSTToVar($_POST);

		
		$validateIDSondage = $this->option->validateIDSondage();
		if( $validateIDSondage !== 1 ){
			$this->addErreur($validateIDSondage);
		}



		//Si au moins un des champs n'est pas valide
		if( !empty($this->erreurs) ){
			if(isset($_POST["redirect"])){//Si le formulaire de creation provient d'une autre page que la page de creation
				//Redirection vers la page contenant le formulaire avec envoi des erreurs
				header("Location:" . $_POST["redirect"] . "&erreurs=" . serialize($this->erreurs));
			} else{
				$this->afficherNouvelleOption(); // On réaffiche le formulaire de creation avec les erreurs
			}

		// Ajout de l'utilisateur en base
		} else{
			

			// Ajout du membre en base et récupération de l'identifiant (ou du message d'erreur)
			$id_option = $this->option->add();
			var_dump($id_option);

			// Si la base de données a bien voulu ajouter l'utliisateur (pas de doublons)
			if (ctype_digit($id_option)) {

				// On transforme la chaine en entier
				$id_option = (int) $id_option;
				
	
	
			// Gestion des doublons
			} else {

				// Changement de nom de variable (plus lisible)
				$erreur =& $id_option;
	
				// On vérifie que l'erreur concerne bien un doublon
				if (23000 == $erreur[0]) { // Le code d'erreur 23000 siginife "doublon" dans le standard ANSI SQL

		

					$this->addErreur("Erreur ajout SQL");
					
	
				} else {
					//Ajout du message d'erreur SQL
					$this->addErreur(sprintf("Erreur ajout SQL (SQLSTATE = %d).", $erreur[0]));
				}
	
				if(isset($_POST["redirect"])){//Si le formulaire d'inscription provient d'une autre page que la page d'inscription
					//Redirection vers la page contenant le formulaire avec envoi des erreurs
					header("Location:" . $_POST["redirect"] . "&erreurs=" . serialize($this->erreurs));
				} else{
					// On reaffiche le formulaire d'inscription avec l'erreur de doublon
					$this->afficherNouvelleOption();
				}
			}			
		}
			
	}

}

?>

<?php
	if( !empty( $_GET["action"] ) && empty($_GET["controller"])){ // Appel d'une méthode de la classe sans passer par l'index.php
		$controller = new controllerUser();
		if( method_exists( $controller , $_GET["action"] ) ){ // Vérification: la méthode demandée existe dans le contrôleur
			if( !empty( $_GET["params"] ) ){
				$controller->$_GET["action"]( $_GET["params"] ); // Exécution de l'action demandée avec des paramètres
			}
			else{
				$controller->$_GET["action"](); // Exécution de l'action demandée sans paramètres
			}
		}
		else{
			$erreur = "Impossible d'effectuer l'action demandée";
		}
	}
?>


	
