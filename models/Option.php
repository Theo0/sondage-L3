<?php 
/* Auteurs: Lucas, Théo */

require_once ROOT. '/models/BD.php';


class Option extends BD{

	private $id;
	private $texte;
	private $id_sondage;

	public function __construct() 
	{
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
	public function constructeurVide()
	{
		$this->id=-1;
		$this->texte=' ';
		$this->id_sondage=-1;
	}

	public function constructeurPlein($idOption)
	{
		$sql='SELECT * FROM
						options
						WHERE
						id=?';
					
		$lectBdd = $this->executerRequete($sql, array($idOption));
		if (($enrBdd = $lectBdd->fetch()) != false)
		{
			$this->id=$idSondage;
			$this->texte=$enrBdd['texte'];
			$this->$id_sondage=$enrBdd['id_sondage'];
		}
		else
		{
			$this->id=-1;
			$this->texte=' ';
			$this->id_sondage=-1;
		}
	}

	public function getId(){
		return $this->id;
	}

	public function getTexte(){
		return $this->texte;
	}

	public function getSondage(){
		return $this->id_sondage;
	}

	public function setId($idOpt){
		if(is_numeric($idOpt)){
			$this->id=$idOpt;
		}
		else{
			return -1;
		}
	}
		
	public function setTexte($texteOpt){
		$this->texte=$texteOpt;
	}

	public function setIdSondage($idSondOpt){
		if(is_numeric($idSondOpt)){
			$this->id_sondage=$idSondOpt;
		}
		else{
			return -1;
		}
	}

	public function update(){
		$sql= 'UPDATE option SET
		texte=?,
		id_sondage=?
		WHERE id=?';

		$this->executerRequete($sql, array($this->texte, $this->id_sondage, $this->id));
	}

	public function remove(){
		$sql='DELETE FROM option WHERE id=?';

		$this->executerRequete($sql, array($this->id));
	}
}
