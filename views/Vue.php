<?php

class Vue {

  // Nom du fichier associé à la vue
  private $fichier;
  // Titre de la vue (défini dans le fichier vue)
  private $titre;
  //Gabarits des vues
  private $fichierGabarit;
  //Erreurs à afficher
  private $erreurs;

  public function __construct($action) {
    // Détermination du nom du fichier vue à partir de l'action
    $this->fichier = ROOT . "/views/vue" . $action . ".php";
    $this->fichierGabarit = ROOT . "/views/gabarit.php";
  }

  public function setErreurs( $tableau_erreurs ){
	$this->erreurs = $tableau_erreurs;
  }

  // Génère et affiche la vue
  public function generer($donnees) {
    // Génération de la partie spécifique de la vue
    $contenu = $this->genererFichier($this->fichier, $donnees);
    // Génération du gabarit commun utilisant la partie spécifique
    $vue = $this->genererFichier($this->fichierGabarit,
      array('titre' => $this->titre, 'contenu' => $contenu, 'erreur' => $this->erreurs));
    // Renvoi de la vue au navigateur
    echo $vue;
  }

  // Génère un fichier vue et renvoie le résultat produit
  private function genererFichier($fichier, $donnees) {
    if (file_exists($fichier)) {
      // Rend les éléments du tableau $donnees accessibles dans la vue
      extract($donnees);
      // Démarrage de la temporisation de sortie
      ob_start();
      // Inclut le fichier vue
      // Son résultat est placé dans le tampon de sortie
      require $fichier;
      // Arrêt de la temporisation et renvoi du tampon de sortie
      return ob_get_clean();
    }
    else {
      throw new Exception("Fichier '$fichier' introuvable");
    }
  }
}

?>
