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
			$this->constructeurPublic();
				break;
		  	 case 1:
			$this->cconstructeurListeAdministre(func_get_arg(0));
				break;
			case 2:
			$this->constructeurListeGroupe(func_get_arg(0), func_get_arg(1));
				break;
			case 3:
			$this->constructeurListeInscrit(func_get_arg(0), func_get_arg(1), func_get_arg(2));
				break;
			case 4:
			$this->constructeurListeComplet(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
				break;
			case 5:
			$this->constructeurListePrive(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4));
				break;
			case 6:
			$this->constructeurListeSousGroupe(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5));
				break;	

		}
	}

	public function constructeurPublic(){
	     $sql='SELECT id, date_creation
                    FROM sondage
                 	WHERE visibilite="public"
		    ORDER BY id DESC';
                               
            $lectBdd = $this->executerRequete($sql, array());
            while (($enrBdd = $lectBdd->fetch()) != false)
            { 

                $this->array_sondage[] = new Sondage($enrBdd["id"]);
            }
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
                $this->array_sondage[] = new Sondage($enrBdd["id"]);
            }
	}

	public function constructeurListeInscrit($a, $b, $c){ //parametres factice pour choix du constructeur
		 $sql='SELECT id
                    FROM sondage
                    WHERE visibilite="inscrits"
		    ORDER BY id DESC';

		 $lectBdd = $this->executerRequete($sql, array());
            while (($enrBdd = $lectBdd->fetch()) != false)
            { 
                $this->array_sondage[] = new Sondage($enrBdd["id"]);
            }   
	}

	public function constructeurListeComplet($idUser, $b, $c , $d){
		$sql='SELECT DISTINCT id_sondage
				FROM user_sondage_reponse
				WHERE id_user=?';
		$lectBdd = $this->executerRequete($sql, array($idUser));
            while (($enrBdd = $lectBdd->fetch()) != false)
            { 
                $this->array_sondage[] = new Sondage($enrBdd["id_sondage"]);
            }   		
	}

	public function constructeurListePrive($idUser, $a, $b, $c , $d){
		$sql='SELECT id_sondage
				FROM user_sondage_votant
				WHERE id_user=?
				UNION 
				SELECT id 
                FROM sondage
                WHERE administrateur_id=? AND visibilite = "prive"';

		$lectBdd = $this->executerRequete($sql, array($idUser, $idUser));
            while (($enrBdd = $lectBdd->fetch()) != false)
            { 
                $this->array_sondage[] = new Sondage($enrBdd["id_sondage"]);
            }   		
	}

	public function constructeurListeSousGroupe($idSousGroupe, $a, $b, $c, $d, $e)
	{ 
           $sql='SELECT id
                    FROM sondage
                    WHERE id_sousgroupe=?
		    ORDER BY id DESC';
                               
            $lectBdd = $this->executerRequete($sql, array($idSousGroupe));
            while (($enrBdd = $lectBdd->fetch()) != false)
            { 
                $this->array_sondage[] = new Sondage($enrBdd["id"]);
            }
	}
	


	public function getArraySondage(){
		return $this->array_sondage;
	}
        
	public function setArraySondage($a){
		$this->array_sondage = $a;
	}

        

	
}

?>
