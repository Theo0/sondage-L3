<?php

require_once ROOT. '/models/BD.php';

class Vote extends BD{

	private $id_sondage;
	private $id_user;
	private $id_option;
	private $classement;


	public function __construct() 
	{
		switch(func_num_args())
		{
			 case 0:
			$this->constructeurVide();
				break;
		  	 case 4:
			$this->constructeurPlein(func_get_arg(0), func_get_arg(1), func_get_arg(2),func_get_arg(3));
				break;
		}
		
	}

	public function constructeurVide(){
		$this->id_sondage = -1;
		$this->id_user = -1;
		$this->id_option = -1;
		$this->classement = -1;

	}

	public function constructeurPlein($idSond, $idUser, $idOption, $class){
		$this->id_sondage = $idSond;
		$this->id_user = $idUser;
		$this->id_option = $idOption;
		$this->classement = $class;

	}


	public function add(){
		$sql = "INSERT INTO `user_sondage_reponse`(`id_sondage`, `id_user`, `id_option`, `classement`) 
		VALUES (?,?,?,?)";
		$insertVote = $this->insererValeur($sql, array($this->id_sondage, $this->id_user, $this->id_option,$this->classement));
		return $insertVote;
	}

	public function addInvite(){
		$sql = "INSERT INTO `invite_sondage_reponse`(`id_sondage`, `ip_user`, `id_option`, `classement`) 
		VALUES (?,?,?,?)";
		$insertVote = $this->insererValeur($sql, array($this->id_sondage, $this->id_user, $this->id_option,$this->classement));
		return $insertVote;
	}


}