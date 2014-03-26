<?php

require_once ROOT . '/models/BD.php';
require_once ROOT . '/models/Sondage.php';

class ListeSondage extends BD {


	private $array_sondage;

	public function __construct() 
	{
            $this->array_sondage = array();
            
		switch(func_num_args())
		{
			 case 0:
			$this->constructeurVide();
				break;
		  	 case 1:
			$this->cconstructeurListeAdministre(func_get_arg(0));
				break;
			case 2:
			$this->constructeurListeAGroupe(func_get_arg(0), func_get_arg(1));
				break;

		}
	}

	public function constructeurVide(){
	    $this->array_sondage = array();
	}

	
	public function cconstructeurListeAdministre($idUser)
	{
            $sql='SELECT id
                    FROM sondage
                    WHERE administrateur_id=?
		    ORDER BY id DESC';
                               
            $lectBdd = $this->executerRequete($sql, array($idUser));
            while (($enrBdd = $lectBdd->fetch()) != false)
            { 

                $this->array_sondage[] = new Sondage($enrBdd["id"]);
            }
	}
	
	
	public function constructeurListeGroupe($idUser, $idGroupe)
	{ 
           $sql='SELECT id
                    FROM sondage
                    WHERE id_groupe=?
		    ORDER BY id DESC';
                               
            $lectBdd = $this->executerRequete($sql, array($idGroupe));
            while (($enrBdd = $lectBdd->fetch()) != false)
            { 
                $this->array_groupes[] = new Sondage($enrBdd["id"]);
            }
	}

	public function getArraySondage(){
		return $this->array_sondage;
	}
        
	public function setArraySondage($a){
		$this->array_sondage = $a;
	}

	/*public function afficherSond($idSond){

		  	$this->array_sondage[] = new Sondage($idSond);

	}*/
        

	
}

?>
