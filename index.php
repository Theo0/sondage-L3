<?php

require "config.php"; //Inclusion du fichier contenant les constantes indispensables au fonctionnement de l'application

/* Si une action est demandée pour un contrôleur */
if( !empty( $_GET["controller"] ) && !empty( $_GET["action"] ) ){
	$controllerName = "Controller" . $_GET["controller"];// Nom du contrôleur demandé

	if( file_exists(ROOT . "/controllers/Controller" . $_GET["controller"] . ".php") ){ //Vérification: contrôleur demandé existe
		include ROOT . "/controllers/Controller" . $_GET["controller"] . ".php"; // Inclusion du contrôleur demandé

		$controller = new $controllerName();// Instanciation du contrôleur demandé

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
	else{
		$erreur = "Impossible d'accéder à la page demandée";
	}
}

if( empty( $_GET["controller"] ) || empty( $_GET["action"] ) || !empty($erreur) ){
	require_once "controllers/ControllerAccueil.php"; // Inclusion du contrôleur de l'accueil

	$controllerAccueil = new ControllerAccueil(); // Instanciation du contrôleur de l'accueil
	
	if( !empty($erreur) ){
		$controllerAccueil->addErreur($erreur);
	}
		$controllerAccueil->afficherAccueil(); // Affichage de l'accueil 

}


?>