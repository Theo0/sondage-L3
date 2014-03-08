<?php

require_once ROOT . '/models/BD.php';
require_once ROOT . '/models/User.php';

class Groupe extends BD {


	private $id;
	private $nom;
	private $administrateurId;
	private $administrateur;
	private $visibilite;
	private $array_membres;
	private $array_moderateurs;


	public function __construct() 
	{
		switch(func_num_args())
		{
			 case 0:
			$this->constructeurVide();
				break;
		  	 case 1:
			$this->constructeurPlein(func_get_arg(0));
				break;
		}
		
	}

	public function constructeurVide(){
		$this->id = -1;
		$this->nom = '';
		$this->administrateurId = -1;
		$this->visibilite = 'public';
		$this->array_membres = array();
		$this->array_moderateurs = array();
	}

	public function constructeurPlein($idGroupe){
		$sql='SELECT groupe.id as groupe_id,
			     groupe.nom as groupe_nom,
			     groupe.administrateur_id as groupe_administrateur_id,
			     groupe.visibilite as groupe_visibilite,
			     user.id as user_id,
			     user.nom as user_nom,
			     user.prenom as user_prenom,
			     user.email as user_email,
			     user.administrateur_site as user_administrateur_site,
			     user.date_inscription as user_date_inscription
			FROM groupe, user
			WHERE groupe.id=?
			AND groupe.administrateur_id=user.id';
					
		$lectBdd = $this->executerRequete($sql, array($idGroupe));
		if (($enrBdd = $lectBdd->fetch()) != false)
		{
			$this->id = $enrBdd['groupe_id'];
			$this->nom = $enrBdd['groupe_nom'];
			$this->administrateurId = $enrBdd['groupe_administrateur_id'];
			$this->visibilite = $enrBdd['groupe_visibilite'];
			
			$this->administrateur = new User();
			$this->administrateur->setId($enrBdd['user_id']);
			$this->administrateur->setNom($enrBdd['user_nom']);
			$this->administrateur->setPrenom($enrBdd['user_prenom']);
			$this->administrateur->setEmail($enrBdd['user_email']);
			$this->administrateur->setAdministrateurSite($enrBdd['user_administrateur_site']);
			$this->administrateur->setDateInscription($enrBdd['user_date_inscription']);
			
		    
			/* Ajout des membres au groupe */
			$sql = 'SELECT id, nom, prenom, administrateur_site, date_inscription
				FROM user_groupe_membre, user
				WHERE id_groupe=?
				AND user_groupe_membre.id_user = user.id';
			
			$lectBdd = $this->executerRequete($sql, array($idGroupe));
			
			$i = 0;
			while (($enrBdd = $lectBdd->fetch()) != false)
			{    
			    $this->array_membres[$i] = new User();
			    $this->array_membres[$i]->setId($enrBdd["id"]);
			    $this->array_membres[$i]->setNom($enrBdd["nom"]);
			    $this->array_membres[$i]->setPrenom($enrBdd["prenom"]);
			    $this->array_membres[$i]->setAdministrateurSite($enrBdd["administrateur_site"]);
			    $this->array_membres[$i]->setDateInscription($enrBdd["date_inscription"]);
			    $i++;
			}
			
			/* Ajout des modérateurs au groupe */
			$sql = 'SELECT id, nom, prenom, administrateur_site, date_inscription
				FROM user_groupe_moderateur, user
				WHERE id_groupe=?
				AND user_groupe_moderateur.id_user = user.id';
			
			$lectBdd = $this->executerRequete($sql, array($idGroupe));
			
			$i = 0;
			while (($enrBdd = $lectBdd->fetch()) != false)
			{    
			    $this->array_moderateurs[$i] = new User();
			    $this->array_moderateurs[$i]->setId($enrBdd["id"]);
			    $this->array_moderateurs[$i]->setNom($enrBdd["nom"]);
			    $this->array_moderateurs[$i]->setPrenom($enrBdd["prenom"]);
			    $this->array_moderateurs[$i]->setAdministrateurSite($enrBdd["administrateur_site"]);
			    $this->array_moderateurs[$i]->setDateInscription($enrBdd["date_inscription"]);
			    $i++;
			}
		} else{
			$this->id = -1;
			$this->nom = '';
			$this->administrateurId = -1;
			$this->visibilite = 'public';
			$this->array_membres = array();
			$this->array_moderateurs = array();
		}
	}

	public function getId(){
		return $this->id;
	}

	public function getNom(){
		return $this->nom;
	}
	
	public function getAdministrateurId(){
		return $this->administrateurId;
	}
	
	public function getAdministrateur(){
		return $this->administrateur;
	}
        
        public function getVisibilite(){
            return $this->visibilite;
        }
	
	public function getArrayMembres(){
		return $this->array_membres;
	}
	
	public function getArrayModerateurs(){
		return $this->array_moderateurs;
	}

	public function setId($i){
		$this->id = $i;
	}

	public function setNom($nom){
		$this->nom = $nom;
	}
	
	public function setAdministrateurId($b){
		$this->administrateurId = $b;
	}
        
        public function setVisibilite($v){
            $this->visibilite = $v;
        }
	
	public function setArrayMembres($a){
		$this->array_membres = $a;
	}
	
	public function setArrayModerateurs($a){
		$this->array_moderateurs = $a;
	}

	/* Vérification de la validité de la visibilite */ 
	public function validateVisibilite(){
		$visibilites = array("public", "privé_visible", "privé_caché");
		
		if(!in_array($this->visibilite, $visibilites)){
			return "La visibilité choisie n'existe pas";
		} else{
			return 1;
		}
	}
	

	/* Formate les variables récupérées d'un formulaire et les stocke dans $this */
	public function POSTToVar($array){
		foreach ($array as $key => $value) {
		    //Suppression des espaces en début et en fin de chaîne
		    $trimedValue = trim($value);
		    //Conversion des tags HTML par leur entité HTML
		    $this->$key = htmlspecialchars($trimedValue);
		}
	}
	
	/* Ajoute un nouveau groupe
	   @return: l'identifiant du groupe ajouté
	*/
	public function add() {
		$sql = 'INSERT INTO groupe SET
			nom = ?,
			administrateur_id = ?,
			visibilite = ?';

		$insertGroupe = $this->insererValeur($sql, array($this->nom, $this->administrateurId , $this->visibilite));

		return $insertGroupe;
	}

	/* Modifie un groupe en base 
	   @return: vrai si le groupe a été modifié, faux sinon
	*/
	public function update() {
		$sql = 'UPDATE groupe SET
			nom = ?,
			administrateur_id = ?,
			visibilite = ?';

		$updateGroupe = $this->executerRequete($sql, array($this->nom, $this->administrateurId , $this->visibilite));

		return ($updateGroupe->rowCount() == 1);
	}


	/* Supprimer un groupe en base
	   @return: vrai si le groupe a été supprimé, faux sinon
	*/
	public function remove() {
		$sql = 'DELETE FROM groupe
			WHERE id = ?';

		$rmGroupe = $this->executerRequete($sql, array($this->id));

		return ($rmGroupe->rowCount() == 1);
	}
	
	/* Supprimer un utilisateur d'un groupe */
	public function quitterGroupe($idUser){
		
		/* Suppression du groupe si c'est l'administrateur qui quitte le groupe */
		$sql = "DELETE FROM groupe
			WHERE administrateur_id=?
			AND id=?";
			
		$rmGroupe = $this->executerRequete($sql, array($idUser, $this->id));
		
		/* Suppression du membre si l'utilisateur est un membre */
		$sql = "DELETE FROM user_groupe_membre
				   WHERE id_user=?
				   AND id_groupe=?";
				   
		$rmGroupeMembre = $this->executerRequete($sql, array($idUser, $this->id));
		
		/* Suppression du moderateur si l'utilisateur est un modérateur */
		$sql = "DELETE FROM user_groupe_moderateur
				   WHERE id_user=?
				   AND id_groupe=?";
				   
		$rmGroupeModerateur = $this->executerRequete($sql, array($idUser, $this->id));
		
		//Retourne vrai si l'utilisateur a été supprimé du groupe
		return ($rmGroupe->rowCount() == 1 || $rmGroupeMembre->rowCount() == 1 || $rmGroupeModerateur->rowCount() == 1);
	}
	
	
	/* Ajoute un membre dans un groupe */
	public function ajouterMembre($idUser){
		$sql = 'INSERT INTO user_groupe_membre SET
			id_groupe = ?,
			id_user = ?';

		$insertGroupe = $this->insererValeur($sql, array($this->id, $idUser));

		return $insertGroupe;		
	}
	
	/* 
	   @return: l'identifiant du groupe ajouté
	*/
	public function isUniqueNom() {
		$sql = 'SELECT id
			FROM groupe
			WHERE nom=?';

		$selectGroupe = $this->executerRequete($sql, array($this->nom));

		return ($selectGroupe->rowCount() == 0);
	}
}

?>
