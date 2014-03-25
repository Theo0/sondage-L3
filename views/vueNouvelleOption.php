<?php 

$this->titre = "Nouveau Sondage";

//Inclusion de la libarairie Form ( http://fr.openclassrooms.com/informatique/cours/votre-site-php-presque-complet-architecture-mvc-et-bonnes-pratiques/gestion-des-formulaires-avec-la-classe-form )
include ROOT.'/models/Form.php';

// "formulaire_nouveau_sondage" est l'ID unique du formulaire
$formulaire_nouvelle_option = new Form('formulaire_nouv_opt', 'POST');

$formulaire_nouvelle_option->action(ABSOLUTE_ROOT . '/controllers/ControllerOption.php?action=NouvelleOption');

$formulaire_nouvelle_option->add('Text', 'titre')
                 ->label("Titre")->Required(true);
		 
$formulaire_nouvelle_option->add('Text', 'description')
                 ->label("Description")->Required(true);

$formulaire_nouvelle_option->add('Radio', 'visiblite')
         ->choices(array(
           'public' => 'public',
           'inscrits' => 'inscrits',
           'groupe' => 'groupe',
           'prive' => 'prive'
         ));
		 //->label("Visibilite")->Required(true);

$formulaire_nouvelle_option->add('Date', 'date_fin')
         ->format('dd/mm/yyyy');

$formulaire_nouvelle_option->add('Radio', 'secret')
        ->choices(array(
          'secret' => 'secret',
          'secret_scrutin' => 'secret_scrutin',
          'public' => 'public'
          ));              

$formulaire_nouvelle_option->add('Submit', 'submit');    



echo $formulaire_nouvelle_option;
