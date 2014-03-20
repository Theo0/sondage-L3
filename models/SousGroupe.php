<?php

require_once ROOT . '/models/BD.php';
require_once ROOT . '/models/User.php';
require_once ROOT . '/models/Groupe.php';

class SousGroupe extends BD {


	private $id;
	private $id_groupe;
        private $groupe;
	private $nom;

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
                $this->id_groupe = -1;
                $this->groupe = new Groupe();
		$this->nom = '';
	}

	public function constructeurPlein($idSousGroupe){
		$sql='SELECT id_groupe, nom
			FROM sous_groupe
			WHERE id=?';
					
		$lectBdd = $this->executerRequete($sql, array($idSousGroupe));
		if (($enrBdd = $lectBdd->fetch()) != false)
		{
			$this->id = $idSousGroupe;
                        $this->id_groupe = $enrBdd['id_groupe'];
                        $this->groupe = new Groupe($this->id_groupe);
			$this->nom = $enrBdd['groupe_nom'];
		} else{
		    $this->id = -1;
                    $this->id_groupe = -1;
                    $this->groupe = new Groupe();
                    $this->nom = '';
		}
	}

	public function getId(){
		return $this->id;
	}

	public function getNom(){
                return $this->nom;
	}
        
        public function getIdGroupe(){
                return $this->id_groupe;
        }
        
        public function getGroupe(){
                return $this->groupe;
        }

	public function setId($i){
		$this->id = $i;
	}

	public function setNom($nom){
		$this->nom = $nom;
	}
	
	public function setIdGroupe($i){
                $this->id_groupe = $i;
        }
        
        public function setGroupe($g){
                $this->groupe = $g;
        }

	/* Retourne vrai si l'utilisateur identifié par $userId est un modérateur du groupe */
	public function isModerateur($userId){
		foreach($this->array_moderateurs as $key=>$moderateur){
			if($moderateur->getId() == $userId){
				return true;
			}
		}
		return false;
	}
	
	/* Retourne vrai si l'utilisateur identifié par $userId est un membre du groupe */
	public function isMembre($userId){
		foreach($this->array_membres as $key=>$membre){
			if($membre->getId() == $userId){
				return true;
			}
		}
		return false;
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
	
	/* Ajoute un nouveau sous groupe
	   @return: l'identifiant du sous groupe ajouté
	*/
	public function add() {
		$sql = 'INSERT INTO sous_groupe SET
			nom = ?,
			id_groupe = ?';

		$insertGroupe = $this->insererValeur($sql, array($this->nom, $this->id_groupe));

		return $insertGroupe;
	}

	/* Modifie un sous groupe en base 
	   @return: vrai si le sous groupe a été modifié, faux sinon
	*/
	public function update() {
		$sql = 'UPDATE sous_groupe SET
			nom = ?,
			id_groupe = ?,
			WHERE id = ?';

		$updateGroupe = $this->executerRequete($sql, array($this->nom, $this->id_groupe , $this->id));

		return ($updateGroupe->rowCount() == 1);
	}


	/* Supprimer un sous groupe en base
	   @return: vrai si le sous groupe a été supprimé, faux sinon
	*/
	public function remove() {
		$sql = 'DELETE FROM sous_groupe
			WHERE id = ?';

		$rmGroupe = $this->executerRequete($sql, array($this->id));

		return ($rmGroupe->rowCount() == 1);
	}
}

?>
