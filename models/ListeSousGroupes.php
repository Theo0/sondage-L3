<?php

require_once ROOT . '/models/BD.php';
require_once ROOT . '/models/SousGroupe.php';

class ListeSousGroupes extends BD {


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

	public function constructeurPlein($idGroupe){
            $sql='SELECT id, id_groupe, nom
                    FROM sous_groupe
                    WHERE id_groupe=?
		    ORDER BY id DESC';
                               
            $lectBdd = $this->executerRequete($sql, array($idGroupe));
            while (($enrBdd = $lectBdd->fetch()) != false){
                $ssGroupe = new SousGroupe();
                $ssGroupe->setId($enrBdd["id"]);
                $ssGroupe->setIdGroupe($idGroupe);
                $ssGroupe->setNom($enrBdd["nom"]);
                
                $this->array_groupes[] = $ssGroupe;
            }
	}

	public function getArrayGroupes(){
		return $this->array_groupes;
	}
        
	public function setArrayGroupes($a){
		$this->array_groupes = $a;
	}
}

?>
