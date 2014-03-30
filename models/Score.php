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

public function getScore(){
	return $this->score;
}

public function calculeScore(){
	
	$sql='SELECT SUM( classement ) 
	FROM user_sondage_reponse
	WHERE id_option =?
	AND id_sondage=?';

	$lectBdd = $this->executerRequete($sql, array($this->id_option, $this->id_sondage));
            $enrBdd = $lectBdd->fetch();
            $this->score = $enrBdd[0];
           
}

}