<?php

require_once ROOT . '/models/BD.php';
require_once ROOT . '/models/Option.php';

class ListeOption extends BD {


	private $array_option;

	public function __construct() 
	{
            $this->array_option = array();
            
		switch(func_num_args())
		{
			 case 0:
			$this->constructeurVide();
				break;
		  	 case 1:
			$this->cconstructeurPlein(func_get_arg(0));
				break;

		}
	}

	public function constructeurVide(){
	    $this->array_option = array();
	}

	
	//Création de la liste des options du sondage passé en paramètre et stockage dans un tableau indexé par leur ID
	public function cconstructeurPlein($idSond)
	{
            $sql="SELECT id
              FROM `option`
              WHERE id_sondage = ?";
                               
            $lectBdd = $this->executerRequete($sql, array($idSond));
            while (($enrBdd = $lectBdd->fetch()) != false)
            { 

                $this->array_option[] = new Option($enrBdd["id"]);
            }

            return $lectBdd;
	}
	


	public function getArrayOption(){
		return $this->array_option;
	}
        
	public function setArrayOption($a){
		$this->array_option = $a;
	}
	
}

?>
