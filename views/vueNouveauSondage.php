<?php 

$this->titre = "Nouveau Sondage";

//Inclusion de la libarairie Form ( http://fr.openclassrooms.com/informatique/cours/votre-site-php-presque-complet-architecture-mvc-et-bonnes-pratiques/gestion-des-formulaires-avec-la-classe-form )
include ROOT.'/models/Form.php';

// "formulaire_nouveau_sondage" est l'ID unique du formulaire
$formulaire_nouveau_sondage = new Form('formulaire_nouv_sond', 'POST');

$formulaire_nouveau_sondage->action(ABSOLUTE_ROOT . '/controllers/ControllerSondage.php?action=nouveauSondage');

$formulaire_nouveau_sondage->add('Text', 'titre')
                 ->label("Titre")->Required(true);
		 
$formulaire_nouveau_sondage->add('Text', 'description')
                 ->label("Description")->Required(true);

$formulaire_nouveau_sondage->add('Radio', 'visiblite')
         ->choices(array(
           'public' => 'public',
           'inscrits' => 'inscrits',
           'groupe' => 'groupe',
           'prive' => 'prive'
         ));
		 //->label("Visibilite")->Required(true);

$formulaire_nouveau_sondage->add('Date', 'date_fin')
         ->format('dd/mm/yyyy');

$formulaire_nouveau_sondage->add('Radio', 'secret')
        ->choices(array(
          'secret' => 'secret',
          'secret_scrutin' => 'secret_scrutin',
          'public' => 'public'
          ));              

$formulaire_nouveau_sondage->add('Submit', 'submit');    




echo $formulaire_nouveau_sondage;
