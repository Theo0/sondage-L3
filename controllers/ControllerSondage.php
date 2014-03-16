<?php

require_once "Controller.php";
require_once ROOT . "/models/Sondage.php";
require_once ROOT . "/models/Mail.php";

class ControllerUser extends Controller{
	
	private $sondage;

	public function __construct() {
		parent::__construct();

		//Création du model utilisateur pour communiquer avec la base de données
		$this->sondage = new Sondage();
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
			

			//Début d'une transaction
			$this->sondage->beginTransaction();

			// Ajout du membre en base et récupération de l'identifiant (ou du message d'erreur)
			$id_sondage = $this->sondage->add();

			// Si la base de données a bien voulu ajouter l'utliisateur (pas de doublons)
			if (ctype_digit($id_utilisateur)) {

				// On transforme la chaine en entier
				$id_sondage = (int) $id_sondage;
	
				// Preparation du mail
				$mail = new Mail('activationInscription', array($this->user->getHashValidation()));
				
	
			// Gestion des doublons
			} else {

				// Changement de nom de variable (plus lisible)
				$erreur =& $id_sondage;
	
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
					$this->afficherNouveauSondage();
				}
			}			
		}
			
	}


	
?>
