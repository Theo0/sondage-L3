<?php


require_once ROOT. '/models/BD.php';


class Score extends BD{

	private $id_option;
	private $id_sondage;
	private $score;


public function __construct() 
	{
		switch(func_num_args())
		{
			 case 0:
			$this->constructeurVide();
				break;
		  	 case 2:
			$this->constructeurPlein(func_get_arg(0), func_get_arg(1));
				break;
			 case 3:
			 $this->constructeurUser(func_get_arg(0), func_get_arg(1), func_get_arg(2));	
		}
		
	}

public function constructeurVide(){
	$this->id_option=-1;
	$this->id_sondage=-1;
	$this->score=-1;
}

public function constructeurPlein($idOpt, $idSond){
	$this->id_option = $idOpt;
	$this->id_sondage = $idSond;
	$this->score=0;
	$this->calculeScore();
}

//On récupère le score attribué par un tulisateur à chaque option et on le stocke dans un tableau
public function constructeurUser($idOpt, $idSond, $idUser){
	$this->id_option = $idOpt;
	$this->id_sondage = $idSond;
	$this->score=0;
	
	$sql = 'SELECT classement FROM user_sondage_reponse
			WHERE id_option=? AND id_sondage=? AND id_user=?';

	$lectBdd = $this->executerRequete($sql, array($this->id_option, $this->id_sondage, $idUser));
            $enrBdd = $lectBdd->fetch();
            $this->score = $enrBdd[0];
}

public function getScore(){
	return $this->score;
}

//Calcul du score total d'un des options
public function calculeScore(){
	
	$sql='SELECT SUM( classement ) 
			FROM (
    			SELECT classement
        		FROM user_sondage_reponse
        		WHERE id_option=? AND id_sondage=?
       				 UNION 
        		SELECT classement 
       			FROM invite_sondage_reponse
        		WHERE id_option=? AND id_sondage=?
    			) as unionuser';

	$lectBdd = $this->executerRequete($sql, array($this->id_option, $this->id_sondage, $this->id_option, $this->id_sondage));
            $enrBdd = $lectBdd->fetch();
            $this->score = $enrBdd[0];
           
}

}