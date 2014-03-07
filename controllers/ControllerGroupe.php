<?php

require_once "Controller.php";
require_once ROOT . "/models/User.php";
require_once ROOT . "/models/Mail.php";
require_once ROOT . "/models/Groupe.php";
require_once ROOT . "/controllers/ControllerUser.php";
require_once ROOT . "/models/ListeGroupes.php";
require_once ROOT . "/controllers/ControllerAccueil.php";

class ControllerGroupe extends Controller{
	
	private $groupe;

	public function __construct() {
		parent::__construct();

		//Création du model groupe
                $this->groupe = new Groupe();
	}

	/* Affichage de la page d'un groupe */
	public function afficherGroupe($idGroupe = null) {
		$this->vue = new Vue("Groupe");

		//Si le contrôlleur possède des erreurs de référencées
		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue

                if(!empty($idGroupe)){
                    $this->groupe = new Groupe($idGroupe);
                }
                
		$this->vue->generer(array("groupe" => $this->groupe));
	}
        
        /* Affichage de la page d'erreur de groupe */
	public function afficherErreurGroupe() {
		$this->vue = new Vue("ErreurGroupe");

		//Si le contrôlleur possède des erreurs de référencées
		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue

		$this->vue->generer(array("erreur" => $this->erreurs[0]));
	}
        
	/* Affichage de la page de la liste des groupes publics */
	public function afficherListeGroupesPublic(){
		$this->vue = new Vue("ListeGroupes");

		//Si le contrôlleur possède des erreurs de référencées
		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue

		$listeGroupes = new ListeGroupes('id', 'DESC', 0, 50, "public");
		
		$this->vue->generer(array("listeGroupes" => $listeGroupes->getArrayGroupes(), "pageSelected" => "public"));		
	}
	
        /* Affiche '1' si le nom du groupe n'existe pas, '0' sinon */
        public function ajaxIsUniqueNomGroupe($nomGroupe){
            if(!empty($nomGroupe)){
                $this->groupe->setNom($nomGroupe);
                echo $this->groupe->isUniqueNom();
            }
            else{
                echo 0;
            }
        }
        
        /* Affiche '1' si le nom du groupe n'existe pas, '0' sinon */
        public function ajaxGetListeGroupes(){
            if(!empty($_SESSION["id"])){
                $listeGroupes = new ListeGroupes($_SESSION['id']);
                $return = array();
                foreach($listeGroupes->getArrayGroupes() as $group){
                    $return[] = array("id"=>$group->getId(), "nom"=>$group->getNom(), "visibilite"=>$group->getVisibilite());
                }
		echo json_encode($return);
            }
            else{
                echo json_encode(array());
            }
        }
        
        /* Affiche '1' si le nom du groupe n'existe pas, '0' sinon */
        public function ajaxSupprimerGroupe($idGroupe){
            if(!empty($nomGroupe)){
                $this->groupe->setId($idGroupe);
                echo $this->groupe->remove();
            }
            else{
                echo 0;
            }
        }
        
        public function creerGroupe(){
		if(empty($_SESSION["id"])){
		    $controllerUser = new ControllerUser();
		    $controllerUser->addErreur("Vous devez vous connecter pour créer un groupe");
		    $controllerUser->afficherConnexion();
		}else{
		    $this->groupe->setAdministrateurId($_SESSION["id"]);
		    if(empty($_POST["nom"])){
			$this->addErreur("Le nom du groupe est requis");
			$this->afficherErreurGroupe();
		    }
		    else{
			 $this->groupe->setNom($_POST["nom"]);
			 
			
			if(false === $this->groupe->isUniqueNom()){
			    $this->addErreur("Le nom de ce groupe n'est pas disponible");
			    $this->afficherErreurGroupe();
			} else{
			    if(empty($_POST["visibilite"])){
				$this->addErreur("La visibilité du groupe est obligatoire");
				$this->afficherErreurGroupe();
			    }else{
				$this->groupe->setVisibilite($_POST["visibilite"]);
				
				if(1 !== $this->groupe->validateVisibilite()){
				    $this->addErreur("La visibilité du groupe n'est pas valide");
				    $this->afficherErreurGroupe();
				}else{
				    $group = $this->groupe->add();
				    if(false !== $group){
					 $listeGroupes = new ListeGroupes($_SESSION['id']);
					$_SESSION['listeGroupes'] = $listeGroupes->getArrayGroupes();
					 $this->afficherGroupe();
				    }
				    else{
					$this->addErreur("Nous sommes dans l'impossibilité d'ajouter le groupe, merci de réessayer ultérieurement");
					$this->afficherErreurGroupe();
				    }
				   
				}
			    }
			}
		    }            
		}
        }
	
	
	/* Supprime l'utilisateur connecté du groupe $idGroupe */
	public function quitterGroupe($idGroupe){
		if(empty($_SESSION["id"])){
			$controllerUser = new ControllerUser();
			$controllerUser->addErreur("Vous devez vous connecter pour quitter un groupe");
			$controllerUser->afficherConnexion();
		}else{
			if(empty($idGroupe)){
				$this->addErreur("Le groupe n'existe pas");
				$this->afficherErreurGroupe();
			} else{
				$this->groupe->setId($idGroupe);
				
				if(false !== $this->groupe->quitterGroupe($_SESSION["id"])){
					$controllerAccueil = new ControllerAccueil();
					$controllerAccueil->afficherAccueil();					
				} else{
					$this->addErreur("Impossible de quitter le groupe");
					$this->afficherErreurGroupe();
				}
			}
		}
	}
}

?>