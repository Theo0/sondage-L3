<!doctype html>
<html lang="fr">
<head>
	<meta charset="UTF-8" />
	<link rel="stylesheet" href="<?= ABSOLUTE_ROOT . '/public/css/responsive/main.css'; ?>" />
	<script src="http://code.jquery.com/jquery-1.9.0rc1.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.0.0rc1.js"></script>
	<script src="<?= ABSOLUTE_ROOT . '/public/js/CryptoJS.js'; ?>"> </script>
	<script src="<?= ABSOLUTE_ROOT . '/public/js/user.js'; ?>"> </script>
	<title><?= NOM_SITE . ' - ' .$titre ?></title>
</head>
<body>

	<header id="header">
		<nav id="navigation">
			<div id="navigation_wrap">
				<div id="conteactinfo"><strong><?= NOM_SITE ?></strong> </div>
				<div id="navi">
					<ul>
						<li><a href="<?= ABSOLUTE_ROOT . '/index.php' ?>">Sondages</a></li>
						<li><a href="<?= ABSOLUTE_ROOT . '/index.php' ?>">Groupes</a></li>
						
						<?php if(empty($_SESSION["id"])): ?>
						<li><a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerUser.php?action=afficherInscription' ?>">Inscription </a></li>
						<li><a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerUser.php?action=afficherConnexion' ?>">Connexion </a></li>
						<?php elseif(!empty($_COOKIE["nom"]) && !empty($_COOKIE["prenom"])): ?>
						<li id="pseudo"><?= $_COOKIE["nom"] . ' ' . $_COOKIE["prenom"] ?></li>
						<? endif; ?>
						
						<?php if(!empty($_SESSION["id"])): ?>
						<li id="deconnexion"><a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerUser.php?action=deconnexion' ?>">Déconnexion</a></li>
						<?php endif; ?>
					</ul>
				</div>
				<!-- End navigation -->
			</div>
		</nav>
		
		<!-- Start H1 Title -->
		<div class="titles">
		
		    <h1><?= $titre ?></h1>
		    
		    <span></span>
		
		</div>
	</header>


	<section id="main">

<div id="main-wrap">
        
        <!-- Start Left Section -->
        <div class="leftsection leftsectionalt">
        
        	<!-- Start Blog Post -->
        	<div class="blogwrapstart">
            
            	<div class="blogtitle"><h3><?= $titre ?></a></div>
                
                <div class="blogbody">
			<div id="contenu">
				<?= $contenu //<==== Affichage de le vue?>
			</div> <!-- #contenu -->
                </div>
                
                <span class="box-arrow"></span>
            
            </div>
            <!-- End Blog Post -->        
        </div>
        <!-- End Left Section -->
        
        <!-- Start Right Section -->
        <div class="rightsection rightsectionalt">
        
        	<!-- Start Blog Widget -->
            <div class="blogwidgetstart">
            	<!-- Start Categories Widget -->
            	<div class="widgettitle"><h4>Groupes</h4></div>
                
                <div class="widgetbody">
                
                	<div class="blogcategories">
                    
                    	<ul>
                        	<li><a href="#" title="All Blogs">All Blogs</a></li>
                            <li><a href="#" title="All Blogs">Lorem ipsum dolor sit</a></li>
                            <li><a href="#" title="All Blogs">Maecenas non ipsum </a></li>
                            <li><a href="#" title="All Blogs">Lorem ipsum dolor sit</a></li>
                            <li><a href="#" title="All Blogs">Maecenas non ipsum </a></li>
                            <li><a href="#" title="All Blogs">Lorem ipsum dolor sit</a></li>
                            <li><a href="#" title="All Blogs">Maecenas non ipsum </a></li>
                            <li><a href="#" title="All Blogs">Lorem ipsum dolor sit</a></li>
                            <li><a href="#" title="All Blogs">Maecenas non ipsum </a></li>
                            <li><a href="#" title="All Blogs">Lorem ipsum dolor sit</a></li>
                            <li><a href="#" title="All Blogs">Maecenas non ipsum </a></li>
                            <li><a href="#" title="All Blogs">Lorem ipsum dolor sit</a></li>
                            <li><a href="#" title="All Blogs">Maecenas non ipsum </a></li>                            
                        </ul>
                    
                    </div>
                
              </div>
              <!-- End Categories Widget -->
              <span class="box-arrow"></span>           
            </div>
            <!-- End Blog Widget -->

        
        </div>
        <!-- End Right Section -->
    
    </div>		
		<div id="erreur">
			<ul>
				<?php if(!empty($erreur)): //Si il existe des erreurs dans la vue?>
					<?php foreach($erreur as $error): //Ecriture de chaque erreur de la vue?>
					<li class="errorEntry"><?= $error ?></li>
					<?php endforeach; ?>
				<?php endif; ?>
			</ul>
		</div> <!-- #erreur -->
		

		<footer id="footerbottom">
			<div class="footerwrap">
				<div id="copyright">&copy;2014.Sondage - Développé par Théo Chambon et Lucas Lafon</div>		
			</div>
		</footer>
	</section> <!-- #global -->
</body>
</html>
