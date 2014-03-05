<?php

/* Variable globales essentielles */
define('ROOT', dirname(__FILE__)); // Chemin de la racine jusqu'au site (à utiliser pour les includes et requires)
define('ABSOLUTE_ROOT', 'http://localhost/sondage-L3'); // Lien absolu de la racine du site (à utiliser pour les vues)

define('NOM_SITE', 'Sondage');

/* Variables de connexion à la base de données */
define('DB_HOST', 'localhost'); //Hôte hébergeant le serveur
define('DB_NAME', 'sondage'); //Nom de la base de donnée
define('DB_USERNAME', 'root'); //Nom d'utilisateur de connexion à la base
define('DB_PASSWD', ''); //Password de connexion à la base
define('DB_CHARSET', 'utf8'); //Méthode d'encodage de la base

/* Variables de configuration de la boîte SMTP */
define('EMAIL_HOST', 'smtp.googlemail.com'); //hôte d'envoi des email
define('EMAIL_PORT', 465); //Port utilisé pour envoyer les emails
define('EMAIL_USERNAME', 'projet.license.info@gmail.com'); //Nom d'utilisateur pour envoyer les emails
define('EMAIL_PASSWD', 'azertyui1'); //Mot de passe associé au compte d'envoi des emails
define('EMAIL_FROM', 'projet.license.info@gmail.com'); //Adresse email de l'envoyeur des emails
define('EMAIL_CHARSET', 'UTF-8'); //Encodage des emails

?>
