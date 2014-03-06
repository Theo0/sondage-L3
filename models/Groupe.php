<?php

require_once ROOT . '/models/BD.php';

class Groupe extends BD {


	private $id;
	private $nom;
	private $administrateurId;
	private $visibilite;


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
	}

	public function constructeurPlein($idGroupe)
	{
		$sql='SELECT *
			FROM groupe
			WHERE id=?';
					
		$lectBdd = $this->executerRequete($sql, array($idGroupe));
		if (($enrBdd = $lectBdd->fetch()) != false)
		{
		    $this->id = $enrBdd['id'];
                    $this->nom = $enrBdd['nom'];
                    $this->administrateurId = $enrBdd['administrateur_id'];
                    $this->visibilite = $enrBdd['visibilite'];
		}
		else 
		{
		    $this->id = -1;
                    $this->nom = '';
                    $this->administrateurId = -1;
                    $this->visibilite = 'public';
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
        
        public function getVisibilite(){
            return $this->visibilite;
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

		$updateGroupe = $this->executerRequete($sql, array($this->id));

		return ($updateGroupe->rowCount() == 1);
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
