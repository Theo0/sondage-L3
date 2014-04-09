<?php
require_once "Controller.php";
require_once ROOT . "/models/User.php";
require_once ROOT . "/controllers/ControllerUser.php";
require_once ROOT . "/models/ListeUser.php";
require_once ROOT . "/models/ConfigAdmin.php";

class ControllerAdmin extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getVue()
    {
        return $this->vue;
    }
    
    // Affiche la page d'accueil du site
    public function afficherAdministration()
    {
        if (empty($_SESSION["id"])) {
            $controllerUser = new ControllerUser();
            $controllerUser->addErreur("Vous devez vous connecter pour accéder à la page d'administration");
            $controllerUser->afficherConnexion();
        } else {
            $user = new User($_SESSION["id"]);
            if ($user->getAdministrateurSite() != 1) {
                $controllerUser = new ControllerUser();
                $controllerUser->addErreur("Vous devez vous connecter pour en tant qu'administrateur pour accéder à la page d'administration");
                $controllerUser->afficherConnexion();
            } else {
                $this->vue = new Vue("Administration");
                if (!empty($this->erreurs)) $this->vue->setErreurs($this->erreurs);
                $listeUser = new ListeUser();
                $configAdmin = new ConfigAdmin();
                $this->vue->generer(array("listeUsers" => $listeUser, "inscriptions" => $configAdmin->getInscriptions()));
            }
        }
    }
    
    public function modifierInscription()
    {	
	if (empty($_SESSION["id"])) {
            $controllerUser = new ControllerUser();
            $controllerUser->addErreur("Vous devez vous connecter pour accéder à la page d'administration");
            $controllerUser->afficherConnexion();
        } else {
            $user = new User($_SESSION["id"]);
            if ($user->getAdministrateurSite() != 1) {
                $controllerUser = new ControllerUser();
                $controllerUser->addErreur("Vous devez vous connecter pour en tant qu'administrateur pour accéder à la page d'administration");
                $controllerUser->afficherConnexion();
            } else {
		$configAdmin = new ConfigAdmin();
		$this->vue = new Vue("Administration");
		
		if(!empty($_POST["inscription"])){ 
		  if($_POST["inscription"]!='ouvertes' && $_POST["inscription"]!="validation"){
		    $this->addErreur("Le type d'inscription spécifié n'existe pas");
		  } else{
		    $configAdmin->setInscriptions($_POST["inscription"]);
		    
		    if(false !== $configAdmin->update()){
		      $this->addErreur("Impossible de modifier la configuration en base"); 
		    } else{
		      $this->vue->setMessage("Configuration modifiée avec succée");
		    }
		  }
		  
		    
		    $listeUser = new ListeUser();
		    
		    if (!empty($this->erreurs)) $this->vue->setErreurs($this->erreurs);
		    
		    $this->vue->generer(array("listeUsers" => $listeUser, "inscriptions" => $configAdmin->getInscriptions()));
		}
            }
        }     
    }
}
?>


<?php
if (!empty($_GET["action"]) && empty($_GET["controller"])) { // Appel d'une méthode de la classe sans passer par l'index.php
    $controller = new controllerAdmin();
    if (method_exists($controller, $_GET["action"])) { // Vérification: la méthode demandée existe dans le contrôleur
        if (!empty($_GET["params"])) {
            $controller->$_GET["action"]($_GET["params"]); // Exécution de l'action demandée avec des paramètres
            
        } else {
            $controller->$_GET["action"](); // Exécution de l'action demandée sans paramètres
            
        }
    } else {
        $erreur = "Impossible d'effectuer l'action demandée";
    }
}
?>
