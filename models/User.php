<?php

require_once ROOT . '/models/BD.php';

class User extends BD {


	private $id;
	private $nom;
	private $prenom;
	private $email;
	private $date_inscription;
	private $mdp_verif;
	private $mdp;
	private $administrateur_site;
	private $compte_valide;


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
		$this->id = -1;
		$this->prenom = '';
		$this->nom = '';
		$this->email = '';
		$this->mdp = '';
		$this->hash_validation = '';
		$this->mdp_verif = '';
		$this->date_inscription='0000-00-00 00:00:00';
		$this->administrateur_site = false;
		$this->compte_valide = 0;

	}

	public function constructeurPlein($idUser)
	{
		$sql='SELECT *
			FROM user
			WHERE id=?';
					
		$lectBdd = $this->executerRequete($sql, array($idUser));
		if (($enrBdd = $lectBdd->fetch()) != false)
		{
			$this->id = $enrBdd['id'];
			$this->prenom = $enrBdd['prenom'];
			$this->nom = $enrBdd['nom'];
			$this->email = $enrBdd['email'];
			$this->hash_validation = $enrBdd['hash_validation'];
			$this->mdp_verif = $enrBdd['password'];
			$this->mdp = $enrBdd['password'];
			$this->date_inscription = $enrBdd['date_inscription'];
			$this->administrateur_site = $enrBdd['administrateur_site'];
			$this->compte_valide = $enrBdd['compte_valide'];
		}
		else 
		{
			$this->id = -1;
			$this->prenom = '';
			$this->nom = '';
			$this->email = '';
			$this->hash_validation = '';
			$this->mdp_verif = '';
			$this->mdp = '';
			$this->date_inscription='0000-00-00 00:00:00';
			$this->administrateur_site = false;
			$this->compte_valide = 0;
		}
	}

	public function getMdp(){
		return $this->mdp;
	}

	public function getMdpVerif(){
		return $this->mdp_verif;
	}

	public function getId(){
		return $this->id;
	}

	public function getNom(){
		return $this->nom;
	}

	public function getPrenom(){
		return $this->prenom;
	}	

	public function getDateInscription(){
		return $this->date_inscription;
	}

	public function getHashValidation(){
		return $this->hash_validation;
	}

	public function getEmail(){
		return $this->email;
	}
	
	public function getAdministrateurSite(){
		return $this->administrateur_site;
	}

	public function setMdp($m){
		$this->mdp = $m;
	}

	public function setMdp_verif($m){
		$this->mdp_verif = $m;
	}

	public function setId($i){
		$this->id = $i;
	}

	public function setNom($nom){
		$this->nom = $nom;
	}

	public function setPrenom($p){
		$this->prenom = $p;
	}	

	public function setHashValidation($h){
		$this->hash_validation = $h;
	}

	public function setEmail($e){
		$this->email = $e;
	}
	
	public function setAdministrateurSite($b){
		$this->administrateur_site = $b;
	}

	public function generateHashValidation(){
		$this->hash_validation = md5(uniqid(rand(), true).$this->email); //génération d'un hashage aléatoire qui sera stocké en base pour l'utilisateur
		return $this->hash_validation;
	}

	/* Vérification de la validité nom d'utilisateur */ 
	public function validateNom(){
		$regexp = "/[a-zA-Z0-9_]{2,15}/";
		if(empty($this->nom) || (!empty($this->nom) && !preg_match($regexp, $this->nom)) ){
			return "Votre nom d'utilisateur doit comporter entre 2 et 15 caractères";
		} else{
			return 1;
		}
	}

	/* Vérification de la validité email */ 
	public function validateEmail(){
		$regexp = '`^[[:alnum:]]([-_.]?[[:alnum:]])*@[[:alnum:]]([-.]?[[:alnum:]])*\.([a-z]{2,4})$`'; 
		if( empty($this->email) || (!empty($this->email) && !preg_match($regexp, $this->email)) ){
			return "Votre adresse email n'est pas valide";
		} else{
			return 1;
		}
	}

	/* Vérification de la validité mot de passe */ 
	public function validateMotDePasse(){
		/*
		$regexp = "/[a-zA-Z0-9\W\D]{6,15}/";
		if( empty($this->mdp) || (!empty($this->mdp) && !preg_match($regexp, $this->mdp)) ){
			return "Votre mot de passe doit comporter entre 6 et 15 caractères";
		} else{
			return 1;
		}
		*/
		if( empty($this->mdp) ){
			return "Votre mot de passe doit comporter entre 6 et 15 caractères";
		} else{
			return 1;
		}
	}

	/* Formate les variables récupérées d'un formulaire et les stocke dans $this */
	public function POSTToVar($array){
		foreach ($array as $key => $value) {
		    //Suppression des espaces en début et en fin de chaîne
		    $trimedValue = trim($value);
		    //Conversion des tags HTML par leur entité HTML
		    $this->$key = htmlspecialchars($trimedValue);
		}
	}

	/* Ajoute un nouvel utilisateur en base (pour l'inscription)
	   @return: l'identifiant de l'utilisateur ajouté
	*/
	public function addUser() {
		$sql = 'INSERT INTO user SET
			nom = ?,
			prenom = ?,
			email = ?,
			password = ?,
			hash_validation = ?,
			date_inscription = NOW()';

		$insertUser = $this->insererValeur($sql, array($this->nom, $this->prenom , $this->email, $this->mdp, $this->generateHashValidation()));

		return $insertUser;
	}

	/* Modifie un utilisateur en base (après l'inscription)
	   @return: vrai si l'utilisateur a été modifié, faux sinon
	*/
	public function updateUser() {
		$sql = 'UPDATE user SET
			nom = ?,
			prenom = ?,
			email = ?,
			password = ?,
			date_inscription = ?,
			administrateur_site = ?,
			hash_validation = ?
			WHERE id = ?';

		$updateUser = $this->executerRequete($sql, array($this->nom,
							       $this->prenom ,
							       $this->email ,
							       $this->mdp ,
							       $this->date_inscription,
							       $this->administrateur_site,
							       $this->hash_validation,
							       $this->id));

		return ($updateUser->rowCount() == 1);
	}

	/* Valide un compte utilisateur avec le numéro de hashage envoyé par email 
	   @return: vrai si le compte a été trouvé, faux sinon 
	*/
	public function valider_compte_avec_hash($hash_validation) {
		$sql = "UPDATE user SET
			compte_valide = true
			WHERE
			hash_validation = ?";

		$activerUser = $this->executerRequete($sql, array($hash_validation));

		return ($activerUser->rowCount() == 1);
	}
	


	public function combinaison_connexion_valide() {
		$sql = "SELECT id, email, nom, prenom FROM user
			WHERE
			email = ? AND 
			password = ? AND
			compte_valide = true";

		$connexion = $this->executerRequete($sql, array($this->email, $this->mdp));

		if ($result = $connexion->fetch(PDO::FETCH_ASSOC)) {
			return $result;
		}
		return false;
	}

	/*Retourne le hash de validation de l'utilisateur si cet utilisateur existe avec l'email $this->email*/
	public function is_valid_email(){
		$sql = "SELECT hash_validation
			FROM user
			WHERE email=?";
			
		$select = $this->executerRequete($sql, array($this->email));
		
		if ($result = $select->fetch(PDO::FETCH_ASSOC)) {
			return $result;
		}
		return false;		
	}
	
	/*Retourne l'identifiant de l'utilisateur si un utilisateur existe avec l'email $email et le hash_validation $hash*/
	public function is_valid_email_hash(){
		$sql = "SELECT id
			FROM user
			WHERE email=?
			AND hash_validation = ?";
			
		$select = $this->executerRequete($sql, array($this->email, $this->hash_validation));
		
		if ($result = $select->fetch(PDO::FETCH_ASSOC)) {
			return $result;
		}
		return false;		
	}
	
	public function construct_with_email_hash(){
		$sql = "SELECT *
			FROM user
			WHERE email=?
			AND hash_validation = ?";
			
		$lectBdd = $this->executerRequete($sql, array($this->email, $this->hash_validation));
		
		if (($enrBdd = $lectBdd->fetch()) != false) {
			$this->id = $enrBdd['id'];
			$this->prenom = $enrBdd['prenom'];
			$this->nom = $enrBdd['nom'];
			$this->email = $enrBdd['email'];
			$this->hash_validation = $enrBdd['hash_validation'];
			$this->mdp_verif = $enrBdd['password'];
			$this->mdp = $enrBdd['password'];
			$this->date_inscription = $enrBdd['date_inscription'];
			$this->administrateur_site = $enrBdd['administrateur_site'];
			$this->compte_valide = $enrBdd['compte_valide'];
			
			return $enrBdd;
		}
		return false;		
	}	
}

?>
