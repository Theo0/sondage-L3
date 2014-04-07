<?php

require_once ROOT . '/models/BD.php';
require_once ROOT . '/models/Sondage.php';
require_once ROOT . '/models/Commentaire.php';

class ListeCommentaire extends BD {

	private $array_commentaires;

	public function __construct() 
	{
            $this->array_commentaires = array();
            
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
	    $this->array_commentaires = array();
	}

	public function constructeurPlein($idSondage)
	{
            $sql='Select id, texte, c.id_commentaire, c.id_user, count(u.id_commentaire) as soutiens
		FROM commentaire c
		LEFT JOIN user_commentaire_like u
		  ON c.id = u.id_commentaire
		WHERE id_sondage = ?
		Group by id
		order by soutiens Desc, id Desc';
                               
            $lectBdd = $this->executerRequete($sql, array($idSondage));
            $i=0;
            while (($enrBdd = $lectBdd->fetch()) != false)
            { 
                $this->array_commentaires[$i] = new Commentaire();
                $this->array_commentaires[$i]->setId($enrBdd["id"]);
                $this->array_commentaires[$i]->setIdSondage($idSondage);
                $this->array_commentaires[$i]->setTexte($enrBdd["texte"]);
                $this->array_commentaires[$i]->setIdCommentaire($enrBdd["id_commentaire"]);
                $this->array_commentaires[$i]->setIdUser($enrBdd["id_user"]);
		$this->array_commentaires[$i]->setSoutiens($enrBdd["soutiens"]);
		
                $i++;
            }
	}

	public function getArrayCommentaires(){
		return $this->array_commentaires;
	}
        
	public function setArrayCommentaires($a){
		$this->array_commentaires = $a;
	}

}

?>
