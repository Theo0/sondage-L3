<?php

require_once ROOT . '/models/BD.php';
require_once ROOT . '/models/User.php';

class Commentaire extends BD {


	private $id;
	private $id_sondage;
	private $id_user;
	private $texte;
	private $id_commentaire;
	private $soutiens;
	private $array_sousCommentaires;

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
		$this->id_sondage = null;
		$this->id_user = -1;
		$this->texte = '';
		$this->id_commentaire = null;
		$this->soutiens = 0;
		$this->array_sousCommentaires = array();
	}

	public function constructeurPlein($idCom){
		$sql='SELECT * FROM commentaire where id = ?';
				
		$lectBdd = $this->executerRequete($sql, array($idCom));
		if (($enrBdd = $lectBdd->fetch()) != false)
		{
                    $this->id = $enrBdd['id'];
                    $this->id_sondage = $enrBdd['id_sondage'];
                    $this->id_user = $enrBdd['id_user'];
                    $this->texte = $enrBdd['texte'];
                    $this->id_commentaire = $enrBdd['id_commentaire'];
		    
		    $sql = 'SELECT COUNT(*) as soutiens FROM user_commentaire_like WHERE id_commentaire = ?';
		    $lectBdd = $this->executerRequete($sql, array($idCom));
		    if (($enrBdd = $lectBdd->fetch()) != false){
			$this->soutiens = $enrBdd['soutiens'];
		    }
		    $this->array_sousCommentaires = array();
		    
		} else{
                    $this->id = -1;
                    $this->id_sondage = null;
                    $this->id_user = -1;
                    $this->texte = '';
                    $this->id_commentaire = null;
		    $this->soutiens = 0;
		    $this->array_sousCommentaires = array();
		}
	}

	public function getId(){
		return $this->id;
	}

	public function getIdSondage(){
		return $this->id_sondage;
	}
        
        public function getTexte(){
            return $this->texte;
        }
	
	public function getIdUser(){
		return $this->id_user;
	}
	
	public function getUser(){
		return new User($this->id_user);
	}
	
	public function getIdCommentaire(){
		return $this->id_commentaire;
	}
	
	public function getSoutiens(){
		return $this->soutiens;
	}
	
	public function getSousCommentaires(){
		return $this->array_sousCommentaires;
	}

	public function setId($i){
		$this->id = $i;
	}

	public function setIdSondage($i){
		$this->id_sondage = $i;
	}
	
	public function setIdUser($b){
		$this->id_user = $b;
	}
        
        public function setTexte($v){
            $this->texte = $v;
        }
	
	public function setSoutiens($v){
            $this->soutiens = $v;
        }
	
	public function setIdCommentaire($a){
		$this->id_commentaire = $a;
        }
	
	public function setSousCommentaires($s){
		$this->array_sousCommentaires = $s;
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
	
	/* Ajoute un nouveau commentaire
	   @return: l'identifiant du commentaire ajouté
	*/
	public function add() {
		$sql = 'INSERT INTO commentaire SET
                        id_sondage = ?,
			texte = ?,
			id_user = ?,
			id_commentaire = ?';

		$insertCommentaire = $this->insererValeur($sql, array($this->id_sondage, $this->texte , $this->id_user, $this->id_commentaire));

		return $insertGroupe;
	}

	/* Modifie un commentaire en base 
	   @return: vrai si le commentaire a été modifié, faux sinon
	*/
	public function update() {
		$sql = 'UPDATE commentaire SET
			id_sondage = ?,
			texte = ?,
			id_user = ?,
			id_commentaire = ?,
			WHERE id = ?';

		$update = $this->executerRequete($sql, array($this->id_sondage, $this->texte , $this->id_user, $this->id_commentaire, $this->id));

		return ($update->rowCount() == 1);
	}


	/* Supprimer un commentaire en base
	   @return: vrai si le commentaire a été supprimé, faux sinon
	*/
	public function remove() {
		$sql = 'DELETE FROM commentaire
			WHERE id = ?';

		$rm = $this->executerRequete($sql, array($this->id));

		return ($rm->rowCount() == 1);
	}
	

	public function ajouterSoutien($idUser) {
		$sql = 'INSERT INTO user_commentaire_like SET
			id_user = ?,
			id_commentaire = ?';
		
		$ins = $this->insererValeur($sql, array($idUser, $this->id));

		return $ins;
	}
	
	public function ajouterSousCommentaire($texteCommentaire, $idUser){
		$sql = 'INSERT INTO commentaire SET
		id_sondage = ?,
		id_user = ?,
		texte = ?,
		id_commentaire = ?;';
		$insertCom = $this->insererValeur($sql, array($this->id_sondage, $idUser , $texteCommentaire, $this->id));
		
		return $insertCom;
	}
	
	
}

?>
