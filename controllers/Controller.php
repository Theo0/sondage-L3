<?php
session_start();

if (!defined('ROOT'))
	require_once("../config.php");
require_once ROOT . '/views/Vue.php';


class Controller{

	protected $erreurs;

	public function __construct() {
		$this->erreurs = array();
	}

	public function addErreur($erreur){
		if(!empty($erreur))
			array_push($this->erreurs, $erreur);
	}
}

?>
