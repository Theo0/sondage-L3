<?php

require_once "Controller.php";


class ControllerAccueil extends Controller{

  private $billet;
  private $vue;

  public function __construct() {
	parent::__construct();
  }

  public function getVue(){
	return $this->vue;
  }

  // Affiche la page d'accueil du site
  public function afficherAccueil() {
    $this->vue = new Vue("Accueil");

    if( !empty($this->erreurs) )
    	$this->vue->setErreurs($this->erreurs);

    $this->vue->generer(array());
  }
}

?>


<?php
	if( !empty( $_GET["action"] ) && empty($_GET["controller"])){ // Appel d'une méthode de la classe sans passer par l'index.php
		$controller = new controllerAccueil();
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
