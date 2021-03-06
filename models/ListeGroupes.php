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
			case 2:
			$this->constructeurListeAdministre(func_get_arg(0), func_get_arg(1));
				break;
			case 5:
			$this->constructeurListeVisibilite(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4));
				break;
		}
	}

	public function constructeurVide(){
	    $this->array_groupes = array();
	}

	public function constructeurPlein($idUser)
	{
            $sql='(SELECT id
                    FROM groupe
                    WHERE administrateur_id=?)
		    UNION
		    (SELECT id_groupe as id
		    FROM user_groupe_membre
		    WHERE id_user=?)
		    UNION
		    (SELECT id_groupe as id
		    FROM user_groupe_moderateur
		    WHERE id_user=?)
		    ORDER BY id DESC';
                               
            $lectBdd = $this->executerRequete($sql, array($idUser, $idUser, $idUser));
            while (($enrBdd = $lectBdd->fetch()) != false)
            { 
                $this->array_groupes[] = new Groupe($enrBdd["id"]);
            }
	}
	
	public function constructeurListeAdministre($idUser, $administre)
	{
            $sql='SELECT id
                    FROM groupe
                    WHERE administrateur_id=?
		    ORDER BY id DESC';
                               
            $lectBdd = $this->executerRequete($sql, array($idUser));
            while (($enrBdd = $lectBdd->fetch()) != false)
            { 
                $this->array_groupes[] = new Groupe($enrBdd["id"]);
            }
	}
	
	
	public function constructeurListeVisibilite($orderBy, $asc, $limitOffset, $limitLignes, $visibilite)
	{ 
            $sql='SELECT id, nom, administrateur_id, visibilite
                    FROM groupe
		    WHERE visibilite=?
		    ORDER BY ? ' . $asc .' 
		    LIMIT ' . $limitOffset . ',' . $limitLignes;
                               
            $lectBdd = $this->executerRequete($sql, array($visibilite, $orderBy));
	    $i = 0;
            while (($enrBdd = $lectBdd->fetch()) != false)
            { 
                $this->array_groupes[$i] = new Groupe($enrBdd["id"]);
		$i++;
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
