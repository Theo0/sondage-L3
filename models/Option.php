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
						`option`
						WHERE
						id=?';
				
		$lectBdd = $this->executerRequete($sql, array($idOption));
		if (($enrBdd = $lectBdd->fetch()) != false)
		{
			$this->id=$idOption;
			$this->texte=$enrBdd['texte'];
			$this->id_sondage=$enrBdd['id_sondage'];
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

	/* Formate les variables récupérées d'un formulaire et les stocke dans $this */
	public function POSTToVar($textOpt, $idSond){
		$this->texte = $textOpt;
		$this->id_sondage= $idSond;
	}

	public function validateIDSondage(){
		if(is_numeric($this->id_sondage))
			{return 1;}
		else
			{return "Le sondage doit être représenté par un ID numérique";}
	}

	//ajout d'un option en base
	public function add(){
		$sql = "INSERT INTO `option`(`texte`, `id_sondage`) VALUES (?,?)";
		$insertOption = $this->insererValeur($sql, array($this->texte, $this->id_sondage));
		return $insertOption;
	}

	//Mise à jour d'une option en base (pas utilisé pour le moment)
	public function update(){
		$sql= 'UPDATE option SET
		texte=?,
		id_sondage=?
		WHERE id=?';

		$updateOption = $this->executerRequete($sql, array($this->texte, $this->id_sondage, $this->id));
		return $updateOption;
	}

	//Suppression d'une option
	public function remove(){
		$sql='DELETE FROM option WHERE id=?';

		$deleteOption = $this->executerRequete($sql, array($this->id));
		return $deleteOption;
	}
}
