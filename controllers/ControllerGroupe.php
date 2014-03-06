<?php

require_once "Controller.php";
require_once ROOT . "/models/User.php";
require_once ROOT . "/models/Mail.php";
require_once ROOT . "models/Groupe.php";

class ControllerGroupe extends Controller{
	
	private $groupe;

	public function __construct() {
		parent::__construct();

		//Création du model groupe
                $this->groupe = new Groupe();
	}

	/* Affichage de la page d'un groupe */
	public function afficherGroupe() {
		$this->vue = new Vue("Groupe");

		//Si le contrôlleur possède des erreurs de référencées
		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue

		$this->vue->generer(array());
	}
        
        
        
}

?>