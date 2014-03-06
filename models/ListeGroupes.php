<?php

require_once ROOT . '/models/BD.php';
require_once ROOT . '/models/Groupe.php';

class ListeGroupes extends BD {


	private $array_groupes;

	public function __construct() 
	{
            $this->array_groupes = array();
            
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
	    $this->array_groupes = array();
	}

	public function constructeurPlein($idUser)
	{
            $sql='SELECT id
                    FROM groupe
                    WHERE administrateur_id=?';
                               
            $lectBdd = $this->executerRequete($sql, array($idUser));
            while (($enrBdd = $lectBdd->fetch()) != false)
            { 
                $this->array_groupes[] = new Groupe($enrBdd["id"]);
            }
	}

	public function getArrayGroupes(){
		return $this->array_groupes;
	}
        
	public function setArrayGroupes($a){
		$this->array_groupes = $a;
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
