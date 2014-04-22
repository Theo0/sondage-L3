 <?php

// Auteur : Théo, Lucas

//REQUIRE
require_once ROOT. '/models/BD.php';
require_once ROOT. '/models/Groupe.php';
require_once ROOT. '/models/SousGroupe.php';



class Sondage extends BD{
	private $id;
	private $titre;
	private $description;
	private $visibilite;
	private $administrateur_id;
	private $date_creation;
	private $date_fin;
	private $secret;
	private $id_groupe;
	private $id_sousgroupe;
	private $user_votant; // permet d'ajouter des utilisateur à un sondage
	private $array_moderateurs;


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
		$this->id_sousgroupe=-1;
		$this->user_votant = -1;
		$this->array_moderateurs = array();
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
			$this->id_sousgroupe=$enrBdd['id_sousgroupe'];
			$this->user_votant = -1;
			
			/* Ajout des modérateurs au sondage */
			$sql = 'SELECT id, nom, prenom, administrateur_site, date_inscription
				FROM user_sondage_moderateur, user
				WHERE id_sondage=?
				AND user_sondage_moderateur.id_user = user.id';
			
			$lectBdd = $this->executerRequete($sql, array($id_sondage));
			
			$this->array_moderateurs = array();
			$i = 0;
			while (($enrBdd = $lectBdd->fetch()) != false)
			{    
			    $this->array_moderateurs[$i] = new User();
			    $this->array_moderateurs[$i]->setId($enrBdd["id"]);
			    $this->array_moderateurs[$i]->setNom($enrBdd["nom"]);
			    $this->array_moderateurs[$i]->setPrenom($enrBdd["prenom"]);
			    $this->array_moderateurs[$i]->setAdministrateurSite($enrBdd["administrateur_site"]);
			    $this->array_moderateurs[$i]->setDateInscription($enrBdd["date_inscription"]);
			    $i++;
			}
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
			$this->id_sousgroupe=-1;
			$this->user_votant = -1;
			$this->array_moderateurs = array();
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

	public function getIdSsGr(){
		return $this->id_sousgroupe;
	}
	
	public function getArrayModerateurs(){
		return $this->array_moderateurs;
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


	public function setDatefin($dateF){
		$this->date_fin=$dateF;

	}

	public function setSecret($secretSond){
		if($secretSond='secret' || $secretSond='secret_scrutin' || $secretSond='public')
		{$this->secret=$secretSond;}
		else
		{return -1;}	
	}

	public function setGroupe($groupSond){
		if(is_numeric($groupSond)){
			$this->id_groupe=$groupSond;
		}
		else
			{return -1;}
	} 

	/* Retourne vrai si l'utilisateur identifié par $userId est un modérateur du sondage */
	public function isModerateur($userId){
		foreach($this->array_moderateurs as $key=>$moderateur){
			if($moderateur->getId() == $userId){
				return true;
			}
		}
		return false;
	}
	
	public function addUser(){

		$sql='INSERT INTO user_sondage_votant SET
				id_sondage=?,
				id_user=?';
		$insertUserSondage = $this->insererValeur($sql, array($this->id, $this->user_votant));		
		return $insertUserSondage;
	}

	public function add() {

		if($this->id_groupe!=-1){
		$sql = 'INSERT INTO sondage SET
		titre=?,
		description=?,
		visibilite=?,
		administrateur_id=?,
		date_creation= NOW(),
		date_fin=?,
		`secret`=?,
		id_groupe=?';
		$insertSondage = $this->insererValeur($sql, array($this->titre, $this->description , $this->visibilite, $this->administrateur_id, $this->date_fin, $this->secret, $this->id_groupe));

		$mod = new Groupe($this->id_groupe);
		$mod = $mod->getArrayModerateurs();

		foreach ($mod as $key => $value) {

			$sql = "INSERT INTO user_sondage_moderateur SET
				id_user=?,
				id_sondage=?";
			$insertMod = $this->insererValeur($sql, array($value->getId(), $insertSondage));
		}

		return $insertSondage;

	}
	else{
		if($this->id_sousgroupe!=-1){
		$sql = 'INSERT INTO sondage SET
		titre=?,
		description=?,
		visibilite=?,
		administrateur_id=?,
		date_creation= NOW(),
		date_fin=?,
		`secret`=?,
		id_sousgroupe=?';
		$insertSondage = $this->insererValeur($sql, array($this->titre, $this->description , $this->visibilite, $this->administrateur_id, $this->date_fin, $this->secret, $this->id_sousgroupe));
		
		$ssGr = new SousGroupe($this->id_sousgroupe);
		$mod = new Groupe($ssGr->getIdGroupe);
		$mod = $mod->getArrayModerateurs();

		foreach ($mod as $key => $value) {

			$sql = "INSERT INTO user_sondage_moderateur SET
				id_user=?,
				id_sondage=?";
			$insertMod = $this->insererValeur($sql, array($value->getId(), $insertSondage));
		}

		return $insertSondage;}
		else{
		$sql = 'INSERT INTO sondage SET
		titre=?,
		description=?,
		visibilite=?,
		administrateur_id=?,
		date_creation= NOW(),
		date_fin=?,
		`secret`=?';
		$insertSondage = $this->insererValeur($sql, array($this->titre, $this->description , $this->visibilite, $this->administrateur_id, $this->date_fin, $this->secret));
		return $insertSondage;}
		}
	}
	

	public function remove(){
		$sql='DELETE FROM sondage WHERE id=?';

		$removeSondage = $this->executerRequete($sql, array($this->id));
		return $removeSondage;
	}
	
	public function nombreOptions(){
		$sql = 'SELECT COUNT( id ) 
		FROM  `option` 
		WHERE  `id_sondage` =?';

		$lectBdd = $this->executerRequete($sql, array($this->id));

		return $nb = $lectBdd->fetch();
	}

	public function ajouterCommentaire($texteCommentaire, $idUser){
		$sql = 'INSERT INTO commentaire SET
		id_sondage = ?,
		id_user = ?,
		texte = ?;';
		$insertCom = $this->insererValeur($sql, array($this->id, $idUser , $texteCommentaire));
		
		return $insertCom;
	}

	public function dejaVote($idUser){

		$sql='SELECT COUNT( * ) 
		FROM user_sondage_reponse
		WHERE id_user =?
		AND id_sondage =?';

		$lectBdd = $this->executerRequete($sql, array($idUser, $this->id));
		return $verifVote = $lectBdd->fetch();

	}

	public function dejaVoteInvite($ipUser){

		$sql='SELECT COUNT( * ) 
		FROM invite_sondage_reponse
		WHERE ip_user =?
		AND id_sondage =?';

		$lectBdd = $this->executerRequete($sql, array($ipUser, $this->id));
		return $verifVote = $lectBdd->fetch();

	}
	
	/* Ajoute un modérateur dans un groupe */
	public function ajouterModerateur($idUser){
		$sql = 'INSERT INTO user_sondage_moderateur SET
			id_sondage = ?,
			id_user = ?';

		$insert = $this->insererValeur($sql, array($this->id, $idUser));

		return $insert;		
	}
	
	/* Supprimer un modérateur dans un sondage */
	public function supprimerModerateur($idUser){
		$sql = 'DELETE FROM user_sondage_moderateur WHERE
			id_sondage = ? AND
			id_user = ?';

		$rm = $this->executerRequete($sql, array($this->id, $idUser));
		
		return ($rm->rowCount() == 1);		
	}

	public function POSTToVarAll($array){
		foreach ($array as $key => $value) {
		    //Suppression des espaces en début et en fin de chaîne
		    $trimedValue = trim($value);
		    //Conversion des tags HTML par leur entité HTML
		    $this->$key = htmlspecialchars($trimedValue);
		}
	}
	


	/* Formate les variables récupérées d'un formulaire et les stocke dans $this */
	public function POSTToVar($array){
			$this->titre=$array["titre"];
			$this->description=$array["description"];
			$this->visibilite=$array["visibilite"];
			$this->administrateur_id=$array["administrateur_id"];
			$this->date_creation=null;
			$this->date_fin=$array["date_fin"];
			$this->secret=$array["secret"];
			if(isset($array["id_groupe"])){
			$this->id_groupe=$array["id_groupe"];
			}
			if(isset($array["id_sousgroupe"])){
			$this->id_sousgroupe=$array["id_sousgroupe"];
			}
	}


}
