<?php

abstract class BD {

	// Objet PDO d'accès à la BD
	private $bdd;

	// Exécute une requête SQL éventuellement paramétrée
	protected function executerRequete($sql, $params = null) {
		if ($params == null) {
			$resultat = $this->getBdd()->query($sql);    // exécution directe
		}
		else {
			$resultat = $this->getBdd()->prepare($sql);  // requête préparée
			$resultat->execute($params);
		}
		//print_r($resultat->errorInfo()); // Message d'erreur
		return $resultat;
	}

	/* Ajoute une ligne dans une table et retourne l'identifiant de cette ligne
	   Retourne l'erreur si la ligne n'a pas pu s'ajouter correctement
	*/
	protected function insererValeur($sql, $params){
		$resultat = $this->getBdd()->prepare($sql);  // requête préparée
		
		if ($resultat->execute($params)) {   
			return $this->getBdd()->lastInsertId(); // Identifiant ajouté
		}

		return $resultat->errorInfo(); // Message d'erreur
	}

	/* Initialisation d'une transaction */
	public function beginTransaction(){
		$this->getBdd()->beginTransaction();
	}

	/* Envoi des requêtes de la transaction */
	public function commit(){
		$this->getBdd()->commit();
	}

	/* Annuler les requêtes précdemment envoyées avec le commit */
	public function rollback(){
		$this->getBdd()->rollback();
	}

	// Renvoie un objet de connexion à la BD en initialisant la connexion au besoin
	protected function getBdd() {
		if ($this->bdd == null) {
			try{
				// Création de la connexion
				$this->bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
				DB_USERNAME, DB_PASSWD);
			}
			catch(Exception $e)
			{
				echo 'Erreur : '.$e->getMessage().'<br />';
				echo 'N° : '.$e->getCode();
				exit();
			}
		}
		return $this->bdd;
	}
	
	/* Fonction appelée pour serialiser le bdd */
	public function __sleep()
	{
	    return array();
	}
}

?>
