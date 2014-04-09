<?php

require_once "Controller.php";
require_once ROOT . "/models/User.php";
require_once ROOT . "/models/Mail.php";
require_once ROOT . "/models/Groupe.php";
require_once ROOT . "/models/SousGroupe.php";
require_once ROOT . "/controllers/ControllerUser.php";
require_once ROOT . "/models/ListeGroupes.php";
require_once ROOT . "/models/ListeSousGroupes.php";
require_once ROOT . "/controllers/ControllerAccueil.php";
require_once ROOT . "/controllers/ControllerSondage.php";

class ControllerGroupe extends Controller{
	
	private $groupe;

	public function __construct() {
		parent::__construct();

		//Création du model groupe
                $this->groupe = new Groupe();
	}

	/* Affichage de la page d'un groupe */
	public function afficherGroupe($idGroupe = null, $message = null) {
		$this->vue = new Vue("Groupe");

		//Si le contrôlleur possède des erreurs de référencées
		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue
			
		//Si on doit afficher un message
		if( !empty($message) ){
			$this->vue->setMessage($message);//Envoi du message à la vue
		}

                if(!empty($idGroupe)){
                    $this->groupe = new Groupe($idGroupe);
		    $sousGroupes = new ListeSousGroupes($idGroupe);
                }
		
		if(!empty($_SESSION['id'])){
			$user = new User($_SESSION["id"]);
		}
		else{
			$user = new User();
		}
		
                
		$this->vue->generer(array("groupe" => $this->groupe, "pageSelected" => "mur", "user" => $user, "sousGroupes" => $sousGroupes));
	}
	
	/* Affichage la liste des membres d'un groupe */
	public function afficherMembresGroupe($idGroupe = null) {
		$this->vue = new Vue("MembresGroupe");

		//Si le contrôlleur possède des erreurs de référencées
		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue

                if(!empty($idGroupe)){
                    $this->groupe = new Groupe($idGroupe);
                }
		
		if(!empty($_SESSION['id'])){
			$user = new User($_SESSION["id"]);
		}
		else{
			$user = new User();
		}
                
		$this->vue->generer(array("groupe" => $this->groupe, "pageSelected" => "membres", "user" => $user));
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
		
		if(isset($_SESSION["id"])){
			$user = new User($_SESSION['id']);
		} else{
			$user = new User();
		}
		
		$this->vue->generer(array("listeGroupes" => $listeGroupes->getArrayGroupes(), "pageSelected" => "public", "user" => $user));		
	}
	
	/* Affichage de la page de la liste des groupes privés visibles */
	public function afficherListeGroupesPrivesVisibles(){
		$this->vue = new Vue("ListeGroupes");

		//Si le contrôlleur possède des erreurs de référencées
		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue

		$listeGroupes = new ListeGroupes('id', 'DESC', 0, 50, "privé_visible");
		
		if(isset($_SESSION["id"])){
			$user = new User($_SESSION['id']);
		} else{
			$user = new User();
		}
		
		$this->vue->generer(array("listeGroupes" => $listeGroupes->getArrayGroupes(), "pageSelected" => "privé_visible", "user" => $user));		
	}
	
	/* Affichage de la page de la liste des groupes dans lequel l'utilisateur connecté appartient */
	public function afficherListeGroupesUser(){
		if(empty($_SESSION["id"])){
		    $controllerUser = new ControllerUser();
		    $controllerUser->addErreur("Vous devez vous connecter pour voir vos groupes");
		    $controllerUser->afficherConnexion();
		}else{
			$this->vue = new Vue("ListeGroupes");
	
			//Si le contrôlleur possède des erreurs de référencées
			if( !empty($this->erreurs) )
				$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue
	
			$listeGroupes = new ListeGroupes($_SESSION["id"]);
			
			$user = new User($_SESSION['id']);
			
			$this->vue->generer(array("listeGroupes" => $listeGroupes->getArrayGroupes(), "pageSelected" => "groupesUser", "user" => $user));
		}
	}
	
	/* Affichage de la page de la liste des groupes dans lequel l'utilisateur connecté est l'administrateur */
	public function afficherListeGroupesAdministres(){
		if(empty($_SESSION["id"])){
		    $controllerUser = new ControllerUser();
		    $controllerUser->addErreur("Vous devez vous connecter pour voir les groupes que vous administrez");
		    $controllerUser->afficherConnexion();
		}else{
			$this->vue = new Vue("ListeGroupes");
	
			//Si le contrôlleur possède des erreurs de référencées
			if( !empty($this->erreurs) )
				$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue
	
			$listeGroupes = new ListeGroupes($_SESSION["id"], true);
			
			$user = new User($_SESSION['id']);
			
			$this->vue->generer(array("listeGroupes" => $listeGroupes->getArrayGroupes(), "pageSelected" => "groupesAdministre", "user" => $user));
		}
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
	
	/* Affiche '1' si le nom du sous groupe n'existe pas, '0' sinon */
        public function ajaxIsUniqueNomSousGroupe($nom_id_groupe){
		$explode = explode(',', $nom_id_groupe);
		$nomSousGroupe = $explode[0];
		$idGroupe = $explode[1];
		
		$this->groupe->setId($idGroupe);
		
		if(!empty($nomSousGroupe)){
		    echo $this->groupe->IsUniqueNomSousGroupe($nomSousGroupe);
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
        
	/* Créé un nouveau groupe */
        public function creerGroupe(){
		if(empty($_SESSION["id"])){
			$controllerUser = new ControllerUser();
			$controllerUser->addErreur("Vous devez vous connecter pour créer un groupe");
			$controllerUser->afficherConnexion();
		}else{
			//L'utilisateur connecté devient l'administrateur du groupe
			$this->groupe->setAdministrateurId($_SESSION["id"]);
			if(empty($_POST["nom"])){
				$this->addErreur("Le nom du groupe est requis");
				$this->afficherErreurGroupe();
			}
			else{
				//Ajout du nom du groupe au modèle
				$this->groupe->setNom($_POST["nom"]);

				if(false === $this->groupe->isUniqueNom()){
					$this->addErreur("Le nom de ce groupe n'est pas disponible");
					$this->afficherErreurGroupe();
				} else{
					if(empty($_POST["visibilite"])){
						$this->addErreur("La visibilité du groupe est obligatoire");
						$this->afficherErreurGroupe();
					}else{
						//Ajout de la visibilité au modèle
						$this->groupe->setVisibilite($_POST["visibilite"]);
						
						if(1 !== $this->groupe->validateVisibilite()){
							$this->addErreur("La visibilité du groupe n'est pas valide");
							$this->afficherErreurGroupe();
						}else{
							//Ajout du groupe en base
							$group = $this->groupe->add();
							if(false !== $group){
								$this->groupe->setId($group);
								
								//Redirection vers la groupe créé
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
	
	/* Supprime le groupe */
	public function supprimerGroupe($idGroupe){
		if(empty($_SESSION["id"])){
			$controllerUser = new ControllerUser();
			$controllerUser->addErreur("Vous devez vous connecter pour supprimer un groupe");
			$controllerUser->afficherConnexion();
		}else{
			if(empty($idGroupe)){
				$this->addErreur("Le groupe n'existe pas");
				$this->afficherErreurGroupe();
			} else{
				$this->groupe->setId($idGroupe);
				
				$user = new User($_SESSION["id"]);
				
				if($user->getAdministrateurSite() == 1 || $_SESSION["id"] == $this->groupe->getAdministrateurId()){
					if(false !== $this->groupe->remove()){
						$this->vue = new Vue("ListeGroupes");
				
						//Si le contrôlleur possède des erreurs de référencées
						if( !empty($this->erreurs) )
							$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue
				
						$listeGroupes = new ListeGroupes('id', 'DESC', 0, 50, "public");
						
						if(isset($_SESSION["id"])){
							$user = new User($_SESSION['id']);
						} else{
							$user = new User();
						}
						
						$this->vue->setMessage("Groupe supprimé avec succés");
						$this->vue->generer(array("listeGroupes" => $listeGroupes->getArrayGroupes(), "pageSelected" => "public", "user" => $user));	
					} else{
						$this->addErreur("Impossible de quitter le groupe");
						$this->afficherErreurGroupe();
					}
				} else{
					$this->addErreur("Impossible de supprimer le groupe si vous n'êtes pas son créateur");
					$this->afficherErreurGroupe();
				}
			}
		}
	}
	
	/* Ajoute un membre au groupe si le groupe est public ou un demande pour le rejoindre sinon */
	public function rejoindreGroupe($idGroupe){
		if(empty($_SESSION["id"])){
			$controllerUser = new ControllerUser();
			$controllerUser->addErreur("Vous devez vous connecter pour rejoindre un groupe");
			$controllerUser->afficherConnexion();
		}else{
			if(empty($idGroupe)){
				$this->addErreur("Le groupe n'existe pas");
				$this->afficherErreurGroupe();
			} else{
				$this->groupe = new Groupe($idGroupe);
				
				if($this->groupe->getVisibilite() == "public")
					$accepter = 1;
				else{
					$accepter = 0;
				}
				
				if(false !== $this->groupe->ajouterMembre($_SESSION["id"], $accepter)){
					if($this->groupe->getVisibilite() == "privé_visible" ||  $this->groupe->getVisibilite() == "privé_caché"){
						$message = 'Votre demande pour rejoindre le groupe a été prise en compte, vous devez attendre la validation d\'un modérateur pour rejoindre le groupe';
					}
					else{
						$message = 'Vous faites maintenant parti du groupe ' . $this->groupe->getNom();
					}
					
					$this->afficherGroupe($idGroupe, $message);					
				} else{
					$this->addErreur("Impossible de rejoindre le groupe");
					$this->afficherErreurGroupe();
				}
			}
		}		
	}
	
	/* Ajoute un membre au groupe */
	public function ajaxAjouterMembreGroupe($user_groupe){
		$explode = explode(',' , $user_groupe);
		$idUser = $explode[0];
		$idGroupe = $explode[1];
		
		
		if(empty($_SESSION["id"])){
			$controllerUser = new ControllerUser();
			$controllerUser->addErreur("Vous devez vous connecter pour ajouter des membres au groupe");
			$controllerUser->afficherConnexion();
		}else{
			$this->groupe = new Groupe($idGroupe);
			$userConnecte = new User($_SESSION["id"]);
			
			if($userConnecte->getId() == $this->groupe->getAdministrateurId() || $userConnecte->getAdministrateurSite() == 1 || $this->groupe->isModerateur($userConnecte->getId())){
				if(false !== $this->groupe->ajouterMembre($idUser))
					echo '1';
				else{
					echo '0';
				}
			} else{
				$this->addErreur("Vous devez être l'administrateur du groupe ou modérateur pour pouvoir modifier les membres");
				$this->afficherGroupe($idGroupe);
			}
		}
	}
	
	/* Supprime un membre au groupe */
	public function ajaxSupprimerMembreGroupe($user_groupe){
		$explode = explode(',' , $user_groupe);
		$idUser = $explode[0];
		$idGroupe = $explode[1];
		
		
		if(empty($_SESSION["id"])){
			$controllerUser = new ControllerUser();
			$controllerUser->addErreur("Vous devez vous connecter pour supprimer des membres au groupe");
			$controllerUser->afficherConnexion();
		}else{
			$this->groupe = new Groupe($idGroupe);
			$userConnecte = new User($_SESSION["id"]);
			
			if($userConnecte->getId() == $this->groupe->getAdministrateurId() || $userConnecte->getAdministrateurSite() == 1 || $this->groupe->isModerateur($userConnecte->getId())){
				echo $this->groupe->supprimerMembre($idUser);
			} else{
				$this->addErreur("Vous devez être l'administrateur du groupe pour pouvoir modifier les membres");
				$this->afficherGroupe($idGroupe);
			}
		}		
	}
	
	/* Ajoute un modérateur au groupe */
	public function ajaxAjouterModerateurGroupe($user_groupe){
		$explode = explode(',' , $user_groupe);
		$idUser = $explode[0];
		$idGroupe = $explode[1];
		
		
		if(empty($_SESSION["id"])){
			$controllerUser = new ControllerUser();
			$controllerUser->addErreur("Vous devez vous connecter pour ajouter des modérateurs au groupe");
			$controllerUser->afficherConnexion();
		}else{
			$this->groupe = new Groupe($idGroupe);
			$userConnecte = new User($_SESSION["id"]);
			
			if($userConnecte->getId() == $this->groupe->getAdministrateurId() || $userConnecte->getAdministrateurSite() == 1){
				echo (ctype_digit($this->groupe->ajouterModerateur($idUser)));
			} else{
				$this->addErreur("Vous devez être l'administrateur du groupe pour pouvoir modifier les modérateurs");
				$this->afficherGroupe($idGroupe);
			}
		}
	}
	
	/* Supprime un modérateur au groupe */
	public function ajaxSupprimerModerateurGroupe($user_groupe){
		$explode = explode(',' , $user_groupe);
		$idUser = $explode[0];
		$idGroupe = $explode[1];
		
		
		if(empty($_SESSION["id"])){
			$controllerUser = new ControllerUser();
			$controllerUser->addErreur("Vous devez vous connecter pour supprimer des modérateurs du groupe");
			$controllerUser->afficherConnexion();
		}else{
			$this->groupe = new Groupe($idGroupe);
			$userConnecte = new User($_SESSION["id"]);
			
			if($userConnecte->getId() == $this->groupe->getAdministrateurId() || $userConnecte->getAdministrateurSite() == 1){
				echo $this->groupe->supprimerModerateur($idUser);
			} else{
				$this->addErreur("Vous devez être l'administrateur du groupe pour pouvoir supprimer les moderateurs");
				$this->afficherGroupe($idGroupe);
			}
		}		
	}
	
	
	/* Ajoute un sous groupe au groupe $idGroupe avec $nom pour nom du sous groupe */
	public function ajouterSousGroupe(){
		//Remplissage du modèle Groupe avec le groupe concerné
		if(!empty($_POST["groupeId"]))
			$this->groupe = new Groupe($_POST["groupeId"]);
		
		//Vérification de l'unicité du nom de sous groupe
		if(!empty($_POST["nom"]) && false === $this->groupe->IsUniqueNomSousGroupe($_POST["nom"])){
			$this->addErreur("Un sous groupe existe déja avec ce nom");
			$this->afficherGroupe($_POST["groupeId"]);
		} else{
			if(empty($_SESSION["id"])){
				$controllerUser = new ControllerUser();
				$controllerUser->addErreur("Vous devez vous connecter pour ajouter des modérateurs au groupe");
				$controllerUser->afficherConnexion();
			}else{
				$sousGroupe = new SousGroupe();
				$sousGroupe->setIdGroupe($_POST["groupeId"]);
				$sousGroupe->setNom($_POST["nom"]);
				
				$userConnecte = new User($_SESSION["id"]);
				
				if($userConnecte->getId() == $this->groupe->getAdministrateurId() || $userConnecte->getAdministrateurSite() == 1){
					if(false === $sousGroupe->add()){
						$controller = new ControllerSondage();
						$controller->addErreur("Impossible d'ajouter les sous groupe en base, merci de réessayer ultèrieurement");
						$controller->afficherSondagesGroupe($_POST["groupeId"]);
					} else{
						$controller = new ControllerSondage();
						$controller->afficherSondagesGroupe($_POST["groupeId"]);
					}
				} else{
					$this->addErreur("Vous devez être l'administrateur du groupe pour pouvoir ajouter des sous groupes");
					$this->afficherGroupe($_POST["groupeId"]);
				}
			}			
		}
		
		
	}
}

?>