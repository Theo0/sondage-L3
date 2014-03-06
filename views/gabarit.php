<!doctype html>
<html lang="fr">
<head>
	<meta charset="UTF-8" />
	<link rel="stylesheet" href="<?= ABSOLUTE_ROOT . '/public/css/responsive/main.css'; ?>" />
	<link rel="stylesheet" href="<?= ABSOLUTE_ROOT . '/public/css/jquery-ui.css'; ?>" />
	<script src="<?= ABSOLUTE_ROOT . '/public/js/jquery-1.9.0rc1.js' ?>"></script>
	<script src="<?= ABSOLUTE_ROOT . '/public/js/jquery-migrate-1.0.0rc1.js' ?>"></script>
	<script src="<?= ABSOLUTE_ROOT . '/public/js/jquery-ui.js' ?>"></script>
	<script src="<?= ABSOLUTE_ROOT . '/public/js/CryptoJS.js'; ?>"> </script>
	<script src="<?= ABSOLUTE_ROOT . '/public/js/user.js'; ?>"> </script>
	<script src="<?= ABSOLUTE_ROOT . '/public/js/groupe.js'; ?>"> </script>
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

			<div id="erreur">
			<ul>
				<?php if(!empty($erreur)): //Si il existe des erreurs dans la vue?>
					<?php foreach($erreur as $error): //Ecriture de chaque erreur de la vue?>
					<li class="errorEntry"><?= $error ?></li>
					<?php endforeach; ?>
				<?php endif; ?>
			</ul>
		</div> <!-- #erreur -->
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
				<?php if(!empty($_SESSION["listeGroupes"])): ?>
					<?php foreach($_SESSION["listeGroupes"] as $groupe): ?>
					<li><a href="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=afficherGroupe&params=' . $groupe->getId() ?>" title="<?= $groupe->getNom() ?>"><?= $groupe->getNom() ?></a></li>
					<?php endforeach; ?>
				<?php endif; ?>
				<li><a href="#" title="Créer un groupe..." id="lienCreerGroupe" onclick="afficherDialogueCreationGroupe()">Créer un groupe</a></li>
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

		

		<footer id="footerbottom">
			<div class="footerwrap">
				<div id="copyright">&copy;2014.Sondage - Développé par Théo Chambon et Lucas Lafon</div>		
			</div>
		</footer>
	</section> <!-- #global -->
	
	
	<div id="dialogCreationGroupe" title="Créer un groupe">
		<form id="formCreationGroupe" action="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=creerGroupe' ?>" method="post" >
			<div id="dialogErreur" class="erreurs"></div>
			<table>
				<tbody>
					<tr>
						<th><label for="nomGroupe">Nom du groupe</label></th>
						<td><input type="text" name="nom" id="nomGroupe" required="required" value="<?php if(!empty($_POST["nom"])) echo $_POST["nom"]; ?>"/></td>
					</tr>
					
				</tbody>
		
				<tbody>
					<tr>
						<th>Confidentialité</th>
						<td>
							<ul>
								<li class="bottomSeparator">
									<div class="visibiliteRadioInput">
										<label for="visibiliteGroupePublic"> Public </label>
										<input type="radio" name="visibilite" id="visibiliteGroupePublic" value="public" <?php if((!empty($_POST["visibilite"]) && $_POST["visibilite"] == "public") || empty($_POST["visibilite"])) echo 'checked="checked"'; ?>/>
										<p>N'importe qui peut afficher le groupe et ses membres.N'importe qui peut le rejoindre sans validation de votre part</p>
									</div>		
								</li>
								<li class="bottomSeparator">
									<div class="visibiliteRadioInput">
										<label for="visibiliteGroupeFerme"> Fermé </label>
										<input type="radio" name="visibilite" id="visibiliteGroupeFerme" value="privé_visible" <?php if(!empty($_POST["visibilite"]) && $_POST["visibilite"] == "privé_visible") echo 'checked="checked"'; ?>/>
										<p>N'importe qui peut afficher le groupe et ses membres. Seul vous pourrez accepter des membres</p>
									</div>	
								</li>
								<li>
									<div class="visibiliteRadioInput">
										<label for="visibiliteGroupeCache"> Secret </label>
										<input type="radio" name="visibilite" id="visibiliteGroupeCache" value="privé_caché" <?php if(!empty($_POST["visibilite"]) && $_POST["visibilite"] == "privé_caché") echo 'checked="checked"'; ?>/>
										<p>Personne ne peut afficher le groupe et ses membres. Seul vous pourrez ajouter des membres</p>
									</div>	
								</li>
							</ul>
		
						</td>
					</tr>
					
				</tbody>
			</table>
			
			<div class="dialogButtons">
				<input id="creerGroupe" type="button" name="creerGroupe" value="Créer"/>
				<input type="button" name="annulerGroupe" value="Annuler" id="boutonAnnulerGroupe" />
			</div>
			
			<script>
				//Listener clic pour bouton créer groupe
				$('#creerGroupe').click(function(){
					creerGroupe($('#nomGroupe').val(), $('#visibiliteGroupePublic').val());
				});
			</script>
		</form>	
	</div>
</body>
</html>
