<?php

require_once ROOT . '/models/BD.php';
require_once ROOT . '/models/User.php';

class ListeUser extends BD{

	private $array_user;


	public function __construct() 
	{
            $this->array_user = array();
            
		switch(func_num_args())
		{
			 case 0:
			$this->constructeurPublic();
				break;
		  	 case 1:
		  	 $this->constructeurUserNonPresent(func_get_arg(0));
		  	 	break;
		}
	}


public function constructeurPublic(){
	     $sql='SELECT id
                    FROM user
		    ORDER BY id DESC';
                               
            $lectBdd = $this->executerRequete($sql, array());
            while (($enrBdd = $lectBdd->fetch()) != false)
            { 

                $this->array_user[] = new User($enrBdd["id"]);
            }
	}

public function constructeurUserNonPresent($idSond){

	     $sql='SELECT id FROM user WHERE id NOT IN (
    			SELECT id_user FROM user_sondage_votant WHERE id_sondage=? )';
		echo $idSond;
                               
            $lectBdd = $this->executerRequete($sql, array($idSond));
            while (($enrBdd = $lectBdd->fetch()) != false)
            { 

                $this->array_user[] = new User($enrBdd["id"]);
            }
	}



public function getArrayUser(){
		return $this->array_user;
	}
        
	public function setArrayUser($a){
		$this->array_user = $a;
	}	
}

?>