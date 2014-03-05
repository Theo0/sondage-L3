<?php
$fromName = NOM_SITE; 
$sujetMail = 'Réinitialisation du mot de passe de votre compte';

$bodyMail = '
<html>
	<head>
	</head>
	<body>
		<p>Bonjour,</p>
                <p>Votre demande de réinitialisation de mot de passe a bien été prise en compte</p>
    		<p>Veuillez cliquer sur <a href="' . ABSOLUTE_ROOT . '/controllers/ControllerUser.php?action=afficherResetMDPPerdu&amp;hash_validation='.$params[0].'&amp;email=' . $params[1] . '">ce lien</a> pour réinitialiser votre mot de passe</p>
    	</body>
</html>';
	
?>


