<?php

require_once "Controller.php";
require_once ROOT . "/models/Option.php";
require_once ROOT . "/models/Mail.php";

class ControllerOption extends Controller{
	
	private $option;

	public function __construct() {
		parent::__construct();

		//Création du model utilisateur pour communiquer avec la base de données
		$this->option = new Option();
	}

	/* Affichage de la page de creation d'une nouvelle option */
	public function afficherNouvelleOption() {
		$this->vue = new Vue("NouvelleOption");

		//Si le contrôlleur possède des erreurs de référencées
		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue

		$this->vue->generer(array());
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

		
		/* Vérification de la validité nom d'utilisateur */ 
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
				$this->afficherNouveauSondage(); // On réaffiche le formulaire de creation avec les erreurs
			}

		// Ajout de l'utilisateur en base
		} else{
			

			//Début d'une transaction
			$this->option->beginTransaction();

			// Ajout du membre en base et récupération de l'identifiant (ou du message d'erreur)
			$id_option = $this->option->add();

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
					
					//Récupération de la valeur dupliquée
					preg_match("`Duplicate entry '(.+)' for key '(.+)'`is", $erreur[2], $valeur_probleme);
					$valeur_probleme = $valeur_probleme[1];
		

					$this->addErreur("Erreur ajout SQL : Doublon");
					
	
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


	
?>