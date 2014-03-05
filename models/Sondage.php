<?php

// Auteur : Théo, Lucas

//REQUIRE
require_once ROOT. '/models/BD.php';

class Sondage extends BD{
	private id;
	private titre;
	private description;
	private visibilite;
	private administrateur_id;
	private date_creation;
	private date_fin;
	private secret;
	private id_groupe;


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


	public function constructeurVide(){
		$this->id=-1;
		$this->titre=" ";
		$this->description=" ";
		$this->visibilite="";
		$this->administrateur_id=-1;
		$this->date_creation=null;
		$this->date_fin=null;
		$this->secret=0;
		$this->id_groupe=-1;
	}

	public function constructeurPlein($id_sondage){

		$sql = 'SELECT * FROM sondage WHERE id = ?';

		$lectBdd = $this->executerRequete($sql, array($id_sondage));

		if (($enrBdd = $lectBdd->fetch()) != false)
		{
			$this->id=$id_sondage;
			$this->titre=$enrBdd['titre'];
			$this->description=$enrBdd['description'];
			$this->visibilite=$enrBdd['visibilite'];
			$this->administrateur_id=$enrBdd['administrateur_id'];
			$this->date_creation=$enrBdd['date_creation'];
			$this->date_fin=$enrBdd['date_fin'];
			$this->secret=$enrBdd['secret'];
			$this->id_groupe=$enrBdd['id_groupe'];
		}
		else
		{
			$this->id=-1;
			$this->titre=" ";
			$this->description=" ";
			$this->visibilite="";
			$this->administrateur_id=-1;
			$this->date_creation=null;
			$this->date_fin=null;
			$this->secret=0;
			$this->id_groupe=-1;			
		}
	}

	//GETTERS

	public function getId(){
		return $this->id;
	}

	public function getTitre(){
		return $this->titre;
	}

	public function getDesc(){
		return $this->description;
	}

	public function getVisibilite(){
		return $this->visibilite;
	}

	public function getAdministrateur(){
		return $this->administrateur_id;
	}

	public function getDateCreation(){
		return $this->date_creation;
	}

	public function getDateFin(){
		return $this->date_fin;
	}

	public function getSecret(){
		return $this->secret;
	}

	public function getIdGr(){
		return $this->id_groupe;
	}

	//SETTERS
	public function setID($idSond){
		if(is_numeric($idSond))
		{$this->id=$idSond;}
		else {return -1;} 
	}

	public function setTitre($titreSond){
		$this->titre=$titreSond;
	}

	public function setDesc($descriptionSond){
		$this->description=$descriptionSond;
	}

	public function setVisibilite($visibiliteSond){
		if($visibiliteSond='public' || $visibiliteSond='inscrits' || $visibiliteSond='groupe' || $visibiliteSond='prive')
		{$this->visibilite=$visibiliteSond;}
		else
		{return -1;}
	}

	public function setAdministrateur($administrateurSond){
		if(is_numeric($administrateurSond))
		{$this->administrateur_id=$administrateurSond;}
		else {return -1;} 
	}


	public function setDatefin(){

	}

	public function setSecret($secretSond){
		if($secretSond='secret' || $secretSond='secret_scrutin' || $secretSond='public')
		{$this->secret=$secretSond;}
		else
		{return -1;}	
	}

	public function setGroupe($groupSond){
		if(is_numeric($groupSond)){
			this->id_groupe=$groupSond;
		}
		else
			{return -1;}
	} 

	public function update(){
		$sql = 'UPDATE sondage SET 
		titre=?,
		description=?,
		visibilite=?,
		administrateur_id=?,
		date_fin=?,
		secret=?,
		id_groupe=?
		WHERE id=?';

		$this->executerRequete($sql, array($this->titre, $this->description, $this->visibilite, $this->administrateur_id, $this->date_fin, $this->secret, $this->id_groupe, $this->id));
	}

	public function remove(){
		$sql='DELETE FROM sondage WHERE id=?';

		$this->executerRequete($sql, array($this->id));
	}
}
