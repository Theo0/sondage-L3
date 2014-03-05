<!doctype html>
<html lang="fr">
<head>
	<meta charset="UTF-8" />
	<link rel="stylesheet" href="<?= ABSOLUTE_ROOT . '/public/css/style.css'; ?>" />
	<link rel="stylesheet" href="<?= ABSOLUTE_ROOT . '/public/css/reset.css'; ?>" />
	<script src="http://code.jquery.com/jquery-1.9.0rc1.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.0.0rc1.js"></script>
	<script src="<?= ABSOLUTE_ROOT . '/public/js/CryptoJS.js'; ?>"> </script>
	<script src="<?= ABSOLUTE_ROOT . '/public/js/user.js'; ?>"> </script>
	<script src="<?= ABSOLUTE_ROOT . '/public/js/jquery.cookie.js'; ?>"> </script>
	<script src="<?= ABSOLUTE_ROOT . '/public/js/panier.js'; ?>"> </script>
	<title><?= NOM_SITE . ' - ' .$titre ?></title>
</head>
<body>

	<header>
		<div id="logo">
			<a href="<?= ABSOLUTE_ROOT . '/index.php' ?>"></a>
		</div>
		<div id ="bloc_session">
			<div id="connexion">
				<a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerUser.php?action=afficherConnexion' ?>">CONNEXION</a>
			</div>

			<div id="inscription">
				<a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerUser.php?action=afficherInscription' ?>">INSCRIPTION </a>
			</div>
		</div>
		<nav>
			<ul>
				<li><a href="<?= ABSOLUTE_ROOT . '/index.php' ?>">Sondages</a></li>
			</ul>
		</nav>
	</header>


	<section id="global">
		<h1 id="titrePage"><?= $titre ?></h1>
		
		<div id="erreur">
			<ul>
				<?php if(!empty($erreur)): //Si il existe des erreurs dans la vue?>
					<?php foreach($erreur as $error): //Ecriture de chaque erreur de la vue?>
					<li class="errorEntry"><?= $error ?></li>
					<?php endforeach; ?>
				<?php endif; ?>
			</ul>
		</div> <!-- #erreur -->
		

		<div id="contenu">
			<?= $contenu //<==== Affichage de le vue?>
		</div> <!-- #contenu -->

		<footer>
			Site réalisé avec PHP, HTML5 et CSS.
		</footer>
	</section> <!-- #global -->
</body>
</html>
