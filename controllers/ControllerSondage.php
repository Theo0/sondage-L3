<?php

require_once "Controller.php";
require_once ROOT . "/models/Sondage.php";
require_once ROOT . "/models/Option.php";
require_once ROOT . "/models/Vote.php";
require_once ROOT . "/models/ListeSondage.php";
require_once ROOT . "/models/ListeOption.php";
require_once ROOT . "/models/User.php";
require_once ROOT . "/models/ListeUser.php";
require_once ROOT . "/models/ListeCommentaire.php";
require_once ROOT . "/models/Score.php";
require_once ROOT . "/models/Mail.php";
require_once ROOT . "/models/ListeSousGroupes.php";

class ControllerSondage extends Controller{
	
	private $sondage;
	private $option;

	public function __construct() {
		parent::__construct();

		//Création du model utilisateur pour communiquer avec la base de données
		$this->sondage = new Sondage();
		$this->option = new Option();
	}

	/* Affichage de la page de creation d'un nouveau sondage */
	public function afficherNouveauSondage() {
		$this->vue = new Vue("NouveauSondage");

		//Si le contrôlleur possède des erreurs de référencées
		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue

		$this->vue->generer(array());
	}

	/* Affichage de la page quand la creation a terminé avec succès */
	public function afficherNouveauSondageTermine(){
		$this->vue = new Vue("NouveauSondageTermine");

		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);

		$this->vue->generer(array());
	}
	


	/* Création d'un sondage avec les informations reçues de $_POST: titre, description, visibilite, id de l'admin, date de fin, secret, et id du groupe */
	public function nouveauSondage(){
		//Récupération des champs du formulaire et insertion dans le modèle
		$this->sondage->POSTToVar($_POST);


		//Si au moins un des champs n'est pas valide
		if( !empty($this->erreurs) ){
			if(isset($_POST["redirect"])){//Si le formulaire de creation provient d'une autre page que la page de creation
				//Redirection vers la page contenant le formulaire avec envoi des erreurs
				header("Location:" . $_POST["redirect"] . "&erreurs=" . serialize($this->erreurs));
			} else{
				$this->afficherNouveauSondage(); // On réaffiche le formulaire de creation avec les erreurs
			}

		// Ajout de l'utilisateur en base
		} else{
			

			// Ajout du membre en base et récupération de l'identifiant (ou du message d'erreur)

			$id_sondage = $this->sondage->add();
			

			// Si la base de données a bien voulu ajouter l'utliisateur (pas de doublons)
			if (ctype_digit($id_sondage)) {


			$this->NouvelleOption($_POST, $id_sondage);
			// Gestion des doublons
			} else {

				// Changement de nom de variable (plus lisible)
				$erreur =& $id_sondage;
	
				// On vérifie que l'erreur concerne bien un doublon
				if (23000 == $erreur[0]) { // Le code d'erreur 23000 siginife "doublon" dans le standard ANSI SQL
		

					$this->addErreur("Erreur ajout SQL");
					
	
				} else {
					//Ajout du message d'erreur SQL
					$this->addErreur(sprintf("Erreur ajout SQL (SQLSTATE = %d).", $erreur[0]));
				}
	
				if(isset($_POST["redirect"])){//Si le formulaire d'inscription provient d'une autre page que la page d'inscription
					//Redirection vers la page contenant le formulaire avec envoi des erreurs
					header("Location:" . $_POST["redirect"] . "&erreurs=" . serialize($this->erreurs));
				} else{
					// On reaffiche le formulaire d'inscription avec l'erreur de doublon
					$this->afficherNouveauSondage();
				}
			}			
		}
			
}


/* Affichage des sondages publics */
public function afficherSondagesPublic(){
		$this->vue = new Vue("ListeSondage");

		//Si le contrôlleur possède des erreurs de référencées
		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue

		//On crée la liste des sondages avec le constructeur par défaut qui prend les sondages publics
		$ListeSondage = new ListeSondage();

		//On vérifie si l'utilisateur connecté est administrateur ou non, pour le transmettre à la vue
		if(isset($_SESSION['id'])){
			$user = new User($_SESSION['id']);
			if($user->getAdministrateurSite() ==1){
				$admin = 1;
			}
			else{
				$admin = 0;
			}
		}
		else{
			$admin = 0;
		}
		
		//Envoie des infos à la vue
		$this->vue->generer(array("ListeSondage" => $ListeSondage->getArraySondage(),  "pageSelected" => "public", "admin" => $admin));	
		

}


//Affichage des sondages reservés aux membres
public function afficherSondagesInscrit(){

	//Vérification de la connexion
	if(empty($_SESSION['id'])){
		?>
		<a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerUser.php?action=afficherConnexion' ?>">Vous devez vous connecter pour accéder à vos sondages. Cliquez ici. </a>
		<?php
	}
	else{
		$this->vue = new Vue("ListeSondage");

		//Si le contrôlleur possède des erreurs de référencées
		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue

		//Paramètres factices car le constructeur dépend du nombre de paramètres
		$a = -1;
		$b = -1;
		$c = -1;

		//On crée une liste de sondage avec le constructeur qui prend 3 paramètres => celui qui crée la liste des sondages réservés aux membres
		$ListeSondage2 = new ListeSondage($a, $b, $c);
		$user = new User($_SESSION['id']);


		if($user->getAdministrateurSite() ==1){
			$admin = 1;
		}
		else{
			$admin = 0;
		}
		
		$this->vue->generer(array("ListeSondage" => $ListeSondage2->getArraySondage(),  "pageSelected" => "sondageInscrit", "admin" => $admin));	
		}

}

/* Affichage des sondages administrés par l'utilisateur connecté */
public function afficherSondagesAdmin(){

	//Vérification de la connexion
	if(empty($_SESSION['id'])){
		?>
		<a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerUser.php?action=afficherConnexion' ?>">Vous devez vous connecter pour accéder à vos sondages. Cliquez ici. </a>
		<?php
	}
	else{	
		$this->vue = new Vue("ListeSondage");

		//Si le contrôlleur possède des erreurs de référencées
		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue


		//On crée la liste des sondages avec l"id de l'user en paramètre
		$ListeSondage = new ListeSondage($_SESSION['id']);

		//On crée un utlisateur pour vérifier son niveau de privilège
		$user = new User($_SESSION['id']);


		//Vérification des privilèges d'admin ou non
		if($user->getAdministrateurSite() ==1){
			$admin = 1;
		}
		else{
			$admin = 0;
		}
		
		$this->vue->generer(array("ListeSondage" => $ListeSondage->getArraySondage(),  "pageSelected" => "sondageAdministre", "admin" => $admin));	
		}	
	}


/* Affichage des sondages complétés par l'user connecté */
public function afficherSondagesComplet(){

	if(empty($_SESSION['id'])){
		?>
		<a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerUser.php?action=afficherConnexion' ?>">Vous devez vous connecter pour accéder à vos sondages. Cliquez ici. </a>
		<?php
	}
	else{	
		$this->vue = new Vue("ListeSondage");

		//Si le contrôlleur possède des erreurs de référencées
		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue
		$b = -1; $c = -1; $d = -1;
		$ListeSondage = new ListeSondage($_SESSION['id'], $b, $c, $d);
		$user = new User($_SESSION['id']);


		if($user->getAdministrateurSite() ==1){
			$admin = 1;
		}
		else{
			$admin = 0;
		}
		
		$this->vue->generer(array("ListeSondage" => $ListeSondage->getArraySondage(),  "pageSelected" => "sondageComplet", "admin" => $admin));	
		}	
	}

public function afficherSondagesPrive(){

	if(empty($_SESSION['id'])){
		?>
		<a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerUser.php?action=afficherConnexion' ?>">Vous devez vous connecter pour accéder à vos sondages. Cliquez ici. </a>
		<?php
	}
	else{	
		$this->vue = new Vue("ListeSondage");

		//Si le contrôlleur possède des erreurs de référencées
		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue
		$a = -1; $b = -1; $c = -1; $d = -1;
		$ListeSondage = new ListeSondage($_SESSION['id'], $a, $b, $c, $d);
		$user = new User($_SESSION['id']);

		if($user->getAdministrateurSite() ==1){
			$admin = 1;
		}
		else{
			$admin = 0;
		}
		
		$this->vue->generer(array("ListeSondage" => $ListeSondage->getArraySondage(),  "pageSelected" => "sondagePrive", "admin" => $admin));	
		}	
	}



//On affiche les sondages d'un groupe
public function afficherSondagesGroupe($idGroupe=null){


	//Vérification de la connexion
	if(empty($_SESSION['id'])){
		?>
		<a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerUser.php?action=afficherConnexion' ?>">Vous devez vous connecter pour accéder à vos sondages. Cliquez ici. </a>
		<?php
	}
	else{	
		$this->vue = new Vue("ListeSondageGroupe");

		//Si le contrôlleur possède des erreurs de référencées
		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue


		//On crée la liste des sondages appartenant au groupe passé en paramètre
		$ListeSondage = new ListeSondage($_SESSION['id'], $idGroupe);


		//Création de l'utilisateur pour vérifier s'il est admin ou non
		$user = new User($_SESSION['id']);

		if($user->getAdministrateurSite() ==1){
			$admin = 1;
		}
		else{
			$admin = 0;
		}
		
		//On crée un groupe avec l'ID passé en paramètre et on crée la liste des sous groupes appartenant à ce groupe
		$groupe = new Groupe($idGroupe);
		$sousGroupes = new ListeSousGroupes($idGroupe);

		$b = -1;

		//On parcours la liste des sous groupes on mets les sondages correspondant à ces sous groupes dans un tableau indexé par les ID des sous groupes, afin de le transmettre à la vue
		foreach($sousGroupes->getArrayGroupes() as $key=>$sousGroupe){
			${$sousGroupe->getId()}= new ListeSondage($sousGroupe->getId() , $b, $b, $b, $b, $b);
			$a = $sousGroupe->getId();
			$ListeSondageSous[$a] = ${$sousGroupe->getId()};
		}
		

		// On transmet à la vue les informations, avec ou sans les sondages des sous groupes selon s'ils existent
		if(isset($ListeSondageSous)){
		$this->vue->generer(array("ListeSondage" => $ListeSondage->getArraySondage(),  "pageSelected" => "sondages", "admin" => $admin, "groupe" => $groupe, "user" => $user, "sousGroupes" => $sousGroupes, "ListeSondagesSous" => $ListeSondageSous));	
		}
		else{
		$this->vue->generer(array("ListeSondage" => $ListeSondage->getArraySondage(),  "pageSelected" => "sondages", "admin" => $admin, "groupe" => $groupe, "user" => $user, "sousGroupes" => $sousGroupes));	
		}
	}	
	}



	//Affichage des informations d'un sondage et des options pour voter
	public function afficherFicheSondage(){
		$this->vue = new Vue("Sondage");
		//Si le contrôlleur possède des erreurs de référencées
			if( !empty($this->erreurs) )
				$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue
	


		//On crée un sondage a l'aide des paramètres de l'URL, et on récupère ses options et ses commentaire, puis on vérifie si l'utilisateur connecté à déjà voté ou non

		$sondage = new Sondage($_GET['params']);

		$ListeOption = new ListeOption($_GET['params']);
	
		$listeCommentaire = new ListeCommentaire($_GET['params']);

		$a = -1;
		$listeUser = new ListeUser($_GET['params'], $a);

		
		if(isset($_SESSION['id'])){
		$this->vue->generer(array("FicheSondage" => $sondage, "ListeOptions" => $ListeOption->getArrayOption(), "listeCommentaires" => $listeCommentaire->getArrayCommentaires(), "DejaVote" => $sondage->dejaVote($_SESSION['id']), "tabUser" => $listeUser->getArrayUser(), "user" => new User($_SESSION["id"])));
		}
		else{
		$this->vue->generer(array("FicheSondage" => $sondage, "ListeOptions" => $ListeOption->getArrayOption(), "listeCommentaires" => $listeCommentaire->getArrayCommentaires(), "DejaVote" => $sondage->dejaVoteInvite($_SERVER["REMOTE_ADDR"]), "tabUser" => $listeUser->getArrayUser(), "user" => new User()));	
		}
	}


//Redirection si un vote est terminé
public function afficherVoteTermine(){

	$this->vue = new Vue("VoteTermine");
	//Si le contrôlleur possède des erreurs de référencées
		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue
	$this->vue->generer(array());	

}

//Fonction permettant de voter
public function ajoutVote(){

	//On récupère le sondage à l'aide du paramètre de l'URL
	$sondage = new Sondage($_GET['params']);

	// On récupère le nombre d"options (pour la méthode de Borda)
	$nbopt = $sondage->nombreOptions();


	// Application de la méthode de Borda :  pour chacune des options on lui attribue un score n en fonction de son classement par l'utilisateur, puis on l'ajoute en base à l'aide de add()
	foreach ($_POST as $key => $value) {


		$n = $value-1;

		$poid = $nbopt[0] - $n;

		if(isset($_SESSION['id'])){

		$vote = new Vote($_GET['params'], $_SESSION['id'], $key, $poid);
		
		$id_vote = $vote->add();

		}
		else{

		$vote = new Vote($_GET['params'], $_SERVER["REMOTE_ADDR"], $key, $poid);
		
		$id_vote = $vote->addInvite();

		}

	}

	//On vérifie que le vote s'est déroulé sans erreurs
	if (ctype_digit($id_vote)) {
			$this->afficherVoteTermine();
		}
		else{
			$erreur =& $id_sondage;
			$this->addErreur(sprintf("Erreur ajout SQL (SQLSTATE = %d).", $erreur[0]));
			$this->afficherFicheSondage();
			
		}
	
}


	//Affichage de la liste des utilisateurs que l'ont peut ajouter à un sondage
	public function afficheAjoutUserSondage(){
		$this->vue = new Vue("AjoutUserSondage");
		//Si le contrôlleur possède des erreurs de référencées
		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue

		if(isset($_GET['params'])){
		$ListeUser = new ListeUser($_GET['params']);
		$sondage = new Sondage($_GET['params']);
		}
		else{
		$ListeUser = new ListeUser();	
		}
		
		$this->vue->generer(array("ListeUser" => $ListeUser->getArrayUser(), "idSondage" => $sondage->getId(), "NomSondage" => $sondage->getTitre()));
	


	}


	public function afficheAjoutUserSondageTermine(){
		$this->vue = new Vue("AjoutUserSondage");
		//Si le contrôlleur possède des erreurs de référencées
		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue

		if(isset($_GET['params'])){
		$ListeUser = new ListeUser($_GET['params']);
		$sondage = new Sondage($_GET['params']);
		}
		else{
		$ListeUser = new ListeUser();
		$sondage = new Sondage();
		}
		
		$this->vue->generer(array("ListeUser" => $ListeUser->getArrayUser(), "membreTermine" => "1", "idSondage" => $sondage->getId(), "NomSondage" => $sondage->getTitre()));
	}

	//Ajout d'un utilisateur à un sondage privé
	public function ajoutUserSondage(){

		//Récupération des champs du formulaire et insertion dans le modèle
		$this->sondage->POSTToVarAll($_POST);


		//Si au moins un des champs n'est pas valide
		if( !empty($this->erreurs) ){
			if(isset($_POST["redirect"])){//Si le formulaire de creation provient d'une autre page que la page de creation
				//Redirection vers la page contenant le formulaire avec envoi des erreurs
				header("Location:" . $_POST["redirect"] . "&erreurs=" . serialize($this->erreurs));
			} else{
				$this->afficheAjoutUserSondage(); // On réaffiche le formulaire de creation avec les erreurs
			}

		// Ajout de l'utilisateur en base
		} else{
			

			// Ajout du membre en base et récupération de l'identifiant (ou du message d'erreur)
			$id_ajout = $this->sondage->addUser();

			// Si la base de données a bien voulu ajouter l'utliisateur (pas de doublons)
			if (ctype_digit($id_ajout)) {

				// On transforme la chaine en entier
				$id_ajout = (int) $id_ajout;
				
				$this->afficheAjoutUserSondageTermine();
	
	
			// Gestion des doublons
			} else {

				// Changement de nom de variable (plus lisible)
				$erreur =& $id_ajout;
	
				// On vérifie que l'erreur concerne bien un doublon
				if (23000 == $erreur[0]) { // Le code d'erreur 23000 siginife "doublon" dans le standard ANSI SQL

		

					$this->addErreur("Erreur ajout SQL");
					
	
				} else {
					//Ajout du message d'erreur SQL
					$this->addErreur(sprintf("Erreur ajout SQL (SQLSTATE = %d).", $erreur[0]));
				}
	
				if(isset($_POST["redirect"])){//Si le formulaire d'inscription provient d'une autre page que la page d'inscription
					//Redirection vers la page contenant le formulaire avec envoi des erreurs
					header("Location:" . $_POST["redirect"] . "&erreurs=" . serialize($this->erreurs));
				} else{
					// On reaffiche le formulaire d'inscription avec l'erreur de doublon
					$this->afficheAjoutUserSondage();
				}
			}			
		}
			

	}
	


	public function NouvelleOption($array, $idSond){
		//Récupération des champs du formulaire et insertion dans le modèle
		
		foreach ($array as $key => $value) {
			if(is_numeric($key)){

		$this->option->POSTToVar($value, $idSond);

		
		$validateIDSondage = $this->option->validateIDSondage();
		if( $validateIDSondage !== 1 ){
			$this->addErreur($validateIDSondage);
		}



		//Si au moins un des champs n'est pas valide
		if( !empty($this->erreurs) ){
			if(isset($array["redirect"])){//Si le formulaire de creation provient d'une autre page que la page de creation
				//Redirection vers la page contenant le formulaire avec envoi des erreurs
				header("Location:" . $array["redirect"] . "&erreurs=" . serialize($this->erreurs));
			} else{
				$this->afficherNouveauSondage(); // On réaffiche le formulaire de creation avec les erreurs
			}

		// Ajout de l'option en base
		} else{
			

			// Ajout du membre en base et récupération de l'identifiant (ou du message d'erreur)
			$id_option = $this->option->add();

			// Si la base de données a bien voulu ajouter l'option (pas de doublons)
			if (ctype_digit($id_option)) {
				
	
	
			// Gestion des doublons
			} else {

				// Changement de nom de variable (plus lisible)
				$erreur =& $id_option;
	
				// On vérifie que l'erreur concerne bien un doublon
				if (23000 == $erreur[0]) { // Le code d'erreur 23000 siginife "doublon" dans le standard ANSI SQL

		

					$this->addErreur("Erreur ajout SQL");
					
	
				} else {
					//Ajout du message d'erreur SQL
					$this->addErreur(sprintf("Erreur ajout SQL (SQLSTATE = %d).", $erreur[0]));
				}
	
				if(isset($array["redirect"])){//Si le formulaire d'ajout provient d'une autre page que la page d'ajout
					//Redirection vers la page contenant le formulaire avec envoi des erreurs
					header("Location:" . $_POST["redirect"] . "&erreurs=" . serialize($this->erreurs));
				} else{
					// On reaffiche le formulaire d'ajout avec l'erreur de doublon
					$this->afficherNouveauSondage();
				}
			}			
			}

			}
		}
 
			$this->afficherNouveauSondageTermine();
			
	}
	

	public function supprimerSondage(){
		$this->vue = new Vue("sondageSupprime");
		//Si le contrôlleur possède des erreurs de référencées
			if( !empty($this->erreurs) )
				$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue

		$sondage = new Sondage($_GET['params']);
		$user = new User($_SESSION['id']);

		if($user->getAdministrateurSite() == 1)	{
			$bdd = $sondage->remove();
			$retour = 1;

		}
		else{
			$retour = 0;
		}

		$this->vue->generer(array("retour" => $retour));
	
	}


		//Gestion des erreurs de la vue
		public function afficherErreur() {
		$this->vue = new Vue("ErreurSondage");

		//Si le contrôlleur possède des erreurs de référencées
		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue

		$this->vue->generer(array("erreur" => $this->erreurs[0]));
	}


	//Affichage des résultats d'un sondage
	public function resultat(){
	$this->vue = new Vue("Resultat");
		//Si le contrôlleur possède des erreurs de référencées
			if( !empty($this->erreurs) )
				$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue
	
		$sondage = new Sondage($_GET['params']);

		if($sondage->getId() == -1){
			$this->addErreur("Erreur : Sondage introuvable !");
			$this->afficherErreur();
			die();
		}

		//On récupère la liste des options et des commentaires du sondage
	
		$ListeOption = new ListeOption($_GET['params']);
	
		$listeCommentaire = new ListeCommentaire($_GET['params']);
		
		$resultat =  array();
		$a = -1;

		$listeUser = new ListeUser($_GET['params'], $a);


		//On récupère les score de chaque options du sondage, puis on les ajoute dans un tableau indexé par l'ID de chacune des options
		foreach($ListeOption->getArrayOption() as $key=>$option){
		${'score'.$option->getId()}= new Score($option->getId() ,$_GET['params']);
		$a = $option->getId();
		$resultat[$a] = ${'score'.$option->getId()};
		
		}

		if(!empty($_SESSION["id"]))
			$user = new User($_SESSION["id"]);
		else
			$user = new User();


		if(isset($_SESSION["id"])){
		$this->vue->generer(array("FicheSondage" => $sondage, "ListeOptions" => $ListeOption->getArrayOption(), "listeCommentaires" => $listeCommentaire->getArrayCommentaires(), "DejaVote" => $sondage->dejaVote($_SESSION['id']), "tabResult" => $resultat, "tabUser" => $listeUser->getArrayUser(), "user" => $user));
		}
		else{
		$this->vue->generer(array("FicheSondage" => $sondage, "ListeOptions" => $ListeOption->getArrayOption(), "listeCommentaires" => $listeCommentaire->getArrayCommentaires(), "DejaVote" => $sondage->dejaVoteInvite($_SERVER["REMOTE_ADDR"]), "tabResult" => $resultat, "tabUser" => $listeUser->getArrayUser(), "user" => $user));		
		}
	}

	/* Ajoute un commentaire à un sondage avec les infos récupérées en $_POST: texteCommentaire et sondageId */
	public function ajaxAjouterCommentaire(){
		if(empty($_POST["texteCommentaire"])){
			echo "Le commentaire ne peut pas être vide";
			die();
		}
		
		if(empty($_POST["sondageId"])){
			echo "Le sondage n'existe pas";
			die();
		}
		
		if(empty($_SESSION["id"])){
			$controllerUser = new ControllerUser();
			$controllerUser->addErreur("Vous devez vous connecter pour ajouter un commentaire à un sondage");
			$controllerUser->afficherConnexion();
		}else{
			$this->sondage = new Sondage($_POST["sondageId"]);
			$userConnecte = new User($_SESSION["id"]);
			$ajoutCom = $this->sondage->ajouterCommentaire($_POST["texteCommentaire"], $userConnecte->getId());
			if(ctype_digit($ajoutCom))
				echo $ajoutCom;
			else{
				echo "Impossible d'ajouter le commentaire";
			}
		}
	}
	
	/* Ajoute un commentaire à un sondage avec les infos récupérées en $_POST: texteCommentaire et sondageId */
	public function ajaxAjouterSousCommentaire(){
		if(empty($_POST["texteCommentaire"])){
			echo "Le commentaire ne peut pas être vide";
			die();
		}
		
		if(empty($_POST["commentaireId"])){
			echo "Le commentaire n'existe pas";
			die();
		}
		
		if(empty($_SESSION["id"])){
			$controllerUser = new ControllerUser();
			$controllerUser->addErreur("Vous devez vous connecter pour ajouter un commentaire à un sondage");
			$controllerUser->afficherConnexion();
		}else{
			$commentaire = new Commentaire($_POST["commentaireId"]);
			$userConnecte = new User($_SESSION["id"]);
			$ajoutCom = $commentaire->ajouterSousCommentaire($_POST["texteCommentaire"], $userConnecte->getId());
			if(ctype_digit($ajoutCom))
				echo $ajoutCom;
			else{
				echo "Impossible d'ajouter le sous commentaire";
			}
		}
	}
	
	/* Ajoute un soutien à un commentaire avec les infos récupérées en $_POST */
	public function ajaxAjouterSoutienCommentaire(){		
		if(empty($_POST["idCom"])){
			echo "Le commentaire n'existe pas";
			die();
		}
		
		if(empty($_SESSION["id"])){
			$controllerUser = new ControllerUser();
			$controllerUser->addErreur("Vous devez vous connecter pour ajouter un soutien");
			$controllerUser->afficherConnexion();
		}else{
			$commentaire = new Commentaire($_POST["idCom"]);
			$userConnecte = new User($_SESSION["id"]);

			if(ctype_digit($commentaire->ajouterSoutien($userConnecte->getId())))
				echo '1';
			else{
				echo "Impossible d'ajouter le soutien";
			}
		}
	}
	
	/* Ajoute un modérateur au sondage */
	public function ajaxAjouterModerateurSondage($user_sondage){
		$explode = explode(',' , $user_sondage);
		$idUser = $explode[0];
		$idSondage = $explode[1];
		
		
		if(empty($_SESSION["id"])){
			$controllerUser = new ControllerUser();
			$controllerUser->addErreur("Vous devez vous connecter pour ajouter des modérateurs au sondage");
			$controllerUser->afficherConnexion();
		}else{
			$this->sondage = new Sondage($idSondage);
			$userConnecte = new User($_SESSION["id"]);
			
			if($userConnecte->getId() == $this->sondage->getAdministrateur() || $userConnecte->getAdministrateurSite() == 1){
				echo (ctype_digit($this->sondage->ajouterModerateur($idUser)));
			} else{
				$this->addErreur("Vous devez être l'administrateur du sondage pour pouvoir modifier les modérateurs");
				$this->afficherFicheSondage($idSondage);
			}
		}
	}
	
	/* Supprime un modérateur au sondage */
	public function ajaxSupprimerModerateurSondage($user_sondage){
		$explode = explode(',' , $user_sondage);
		$idUser = $explode[0];
		$idSondage = $explode[1];
		
		
		if(empty($_SESSION["id"])){
			$controllerUser = new ControllerUser();
			$controllerUser->addErreur("Vous devez vous connecter pour supprimer des modérateurs du groupe");
			$controllerUser->afficherConnexion();
		}else{
			$this->sondage = new Sondage($idSondage);
			$userConnecte = new User($_SESSION["id"]);
			
			if($userConnecte->getId() == $this->sondage->getAdministrateur() || $userConnecte->getAdministrateurSite() == 1){
				echo $this->sondage->supprimerModerateur($idUser);
			} else{
				$this->addErreur("Vous devez être l'administrateur du sondage pour pouvoir supprimer les moderateurs");
				$this->afficherFicheSondage($idSondage);
			}
		}		
	}
	
	/* Supprime un modérateur au sondage */
	public function ajaxSupprimerCommentaire($idCom = null){
		if(empty($_POST['commentaireId']))
			echo 'Aucun commentaire à supprimer';
		
		
		if(empty($_SESSION["id"])){
			$controllerUser = new ControllerUser();
			$controllerUser->addErreur("Vous devez vous connecter pour supprimer des commentaires");
			$controllerUser->afficherConnexion();
		}else{
			$userConnecte = new User($_SESSION["id"]);
			$commentaire = new Commentaire($_POST['commentaireId']);
			$this->sondage = new Sondage($commentaire->getIdSondage());
			
			if($userConnecte->getId() == $this->sondage->getAdministrateur() || $userConnecte->getAdministrateurSite() == 1 || $this->sondage->isModerateur($userConnecte->getId())){
				echo $commentaire->remove();
			} else{
				$this->addErreur("Vous devez être l'administrateur du sondage pour pouvoir supprimer les moderateurs");
				$this->afficherFicheSondage($idSondage);
			}
		}		
	}
}

?>

<?php
	if( !empty( $_GET["action"] ) && empty($_GET["controller"])){ // Appel d'une méthode de la classe sans passer par l'index.php
		$controller = new controllerSondage();
		if( method_exists( $controller , $_GET["action"] ) ){ // Vérification: la méthode demandée existe dans le contrôleur
			if( !empty( $_GET["params"] ) ){
				$controller->$_GET["action"]( $_GET["params"] ); // Exécution de l'action demandée avec des paramètres
			}
			else{
				$controller->$_GET["action"](); // Exécution de l'action demandée sans paramètres
			}
		}
		else{
			$erreur = "Impossible d'effectuer l'action demandée";
		}
	}
?>


	
