<?php 
require_once ROOT . "/models/Score.php";
$this->titre = $FicheSondage->getTitre(); 
?>
<input id="idSondage" type="hidden" value="<?= $FicheSondage->getId() ?>" />

<?php if($user->getId() == $FicheSondage->getAdministrateur() || $user->getAdministrateurSite() == 1 || $FicheSondage->isModerateur($user->getId())): //Si l'utilisateur connecté est l'administrateur du groupe ou l'administrateur du site ?>
<input id="canDelete" type="hidden" value="1" />
<?php endif; ?>

<div id="ficheSondage">
<?php if(empty($FicheSondage)){ ?>
<?php $this->titre = "Sondage non trouvé"; ?>
<p>Erreur : Aucun sondage séléctionné !</p>
<?php } else{ ?>
<?php 
require_once ROOT . "/models/Score.php";
$this->titre = $FicheSondage->getTitre();
$id = 1;
$op = 10000;
 ?>
<h1><?php
echo($FicheSondage->getTitre());
 ?></h1>
 <br />
 <h3><?php
echo($FicheSondage->getDesc());
 ?></h3>
 <br />
<?php if($DejaVote[0]==0){ ?> 
<p>Ordonnez vos réponses parmis les choix ci-dessous (1 étant votre choix préféré).<br />
Vous pouvez classer plusieurs options ex-aequo.</p>
<form method="POST" action=" <?= ABSOLUTE_ROOT . '/controllers/ControllerSondage.php?action=ajoutVote&params=' .$_GET['params']; ?>" id="formulaire_vote" >
<?php foreach($ListeOptions as $key=>$option){ 
	$id = $id+1;
	$op = $op+1;
	?>
<?php echo($option->getTexte()); 
echo '<select name="' .$option->getId(). '">';
 ?>
<?php
for ($i=0 ; $i < sizeof($ListeOptions) ; $i++ ) { 
	$num = $i+1;
 	echo "<option value='" .$num. "'>" . $num . "</option>";
 } 
?>
</select>
<?php
 if($FicheSondage->getSecret() == "public"): ?>
<script>
  $(function() {
    $( "<?='#'.$id;?>" ).dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 100
      },
      hide: {
        effect: "explode",
        duration: 100
      }
    });
 
    $( "<?='#'.$op;?>" ).click(function() {
      $( "<?='#'.$id;?>").dialog( "open" );
    });
  });
  </script>
  <div id='<?=$id;?>' title="Score attribué">
  	<?php
  	if(empty($tabUser)){echo "Aucun vote !";}
   foreach ($tabUser as $key => $value) {
    	echo $value->getNom() . "   " . $value->getPrenom();
    	$scoreUser = new Score($option->getId(), $FicheSondage->getId(), $value->getId());
    	echo " : " . $scoreUser->getScore() . "<br />";

    } 
   ?>
   </div>
   <button type='button' id='<?=$op;?>'>Afficher les votants</button>



<?php
endif;
echo "<br />";
 } ?>

<input type="submit" value="Voter">
</form>

<?php }
else{
	$id = 1;
	$op = 10000;
	echo "<b>Vous avez déjà répondu à ce sondage !<b><br />";
	foreach($ListeOptions as $key=>$option){ 

		$id = $id+1;
		$op = $op+1;
		echo $option->getTexte();

		if($FicheSondage->getSecret() == "public"): ?>
		<script>
  $(function() {
    $( "<?='#'.$id;?>" ).dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 100
      },
      hide: {
        effect: "explode",
        duration: 100
      }
    });
 
    $( "<?='#'.$op;?>" ).click(function() {
      $( "<?='#'.$id;?>").dialog( "open" );
    });
  });
  </script>
  <div id='<?=$id;?>' title="Score attribué">
  	<?php
  	if(empty($tabUser)){echo "Aucun vote !";}
   foreach ($tabUser as $key => $value) {
    	echo $value->getNom() . "   " . $value->getPrenom();
    	$scoreUser = new Score($option->getId(), $FicheSondage->getId(), $value->getId());
    	echo " : " . $scoreUser->getScore() . "<br />";

    } 
   ?>
   </div>
   <button type='button' id='<?=$op;?>'>Afficher les votants</button>
<?php
endif;
echo "<br />";
	}
} 
}
?>
</div>

<!-- AFFICHAGE DE LA LISTE DES MODERATEURS DU SONDAGE -->
<div id="divModerateursSondage">
        <h4>Modérateurs</h4>
        <ul id="listeModerateursSondage" class="listeModerateurs">
        <?php if(!empty($FicheSondage->getArrayModerateurs())): ?>
                <?php foreach($FicheSondage->getArrayModerateurs() as $moderateur): ?>
                <li class="user<?= $moderateur->getId() ?>">
                        <?= $moderateur->getPrenom() . ' ' . $moderateur->getNom() ?>
                
                        <?php if($user->getId() == $FicheSondage->getAdministrateur() || $user->getAdministrateurSite() == 1): //Si l'utilisateur connecté est l'administrateur du groupe ou l'administrateur du site ?>
                        <span class="lienSupprimerMembre"><a href="#" id="supprimerModerateur<?= $moderateur->getId() ?>" onclick="supprimerModerateurSondage(<?= $moderateur->getId() ?>, <?= $FicheSondage->getId() ?>)"> supprimer </a></span>
                        <?php endif; ?>
                </li>
                <li class="aucunModerateur" style="display: none;">Il n'y a aucun modérateur dans ce sondage</li>
                <?php endforeach; ?>
                <?php else: ?>
                <li class="aucunModerateur">Il n'y a aucun modérateur dans ce sondage</li>
                <?php endif; ?>
         </ul>
        <?php if($user->getId() == $FicheSondage->getAdministrateur() || $user->getAdministrateurSite() == 1): //Si l'utilisateur connecté est l'administrateur du groupe ou l'administrateur du site ?>
        <p class="lienAjoutGroupe"><a href="#" onclick="afficherDialogueAjoutModerateurSondage()"> Ajouter un moderateur </a></p>

        <?php endif; ?>
</div>
<!-- FIN LISTE DES MODERATEURS -->


<!-- AFFICHAGE DE LA LISTE DES COMMENTAIRES DU SONDAGE -->
<div id="containerCommentaires">
  <ul id="listeCommentaires">
    <?php foreach($listeCommentaires as $key=>$commentaire): ?>
    <li class="commentaire" id="commentaire<?= $commentaire->getId() ?>">
      <p class="pseudoCommentaire"><?= $commentaire->getUser()->getPrenom() . ' ' . $commentaire->getUser()->getNom() ?></p> 
      <span class="texteCommentaire"><?= $commentaire->getTexte() ?></span>
      
      <?php if($user->getId() == $FicheSondage->getAdministrateur() || $user->getAdministrateurSite() == 1): //Si l'utilisateur connecté est l'administrateur du groupe ou l'administrateur du site ?>
        <span><a href="#" onclick="supprimerCommentaire(<?= $commentaire->getId() ?>)"><img src="http://localhost/sondage-L3/public/css/images/red-cross.png"> </a></span>
        <?php endif; ?>
      
      
      <span class="blocSoutiens"> 
        <span id="soutien<?=$commentaire->getId() ?>" ><?= $commentaire->getSoutiens() ?> </span>
        <span> <img src="<?= ABSOLUTE_ROOT . '/public/img/facebook-like-icon.png' ?>" onclick="ajouterSoutien( <?=$commentaire->getId() ?>)" /></span>
      </span>
      
      <ul class="listeSousCommentaires" id="listeSousCommentaires<?= $commentaire->getId() ?>">
        <?php foreach($commentaire->getSousCommentaires() as $key=>$sousCommentaire): ?>
        <li class="sousCommentaire" id="sousCommentaire<?= $sousCommentaire->getId() ?>">
        <span class="pseudoCommentaire"><?= $sousCommentaire->getUser()->getPrenom() . ' ' . $sousCommentaire->getUser()->getNom() ?></span> - <?= $sousCommentaire->getTexte() ?>
        <span><a href="#" onclick="supprimerCommentaire(<?= $sousCommentaire->getId() ?>)"><img src="http://localhost/sondage-L3/public/css/images/red-cross.png"> </a></span></li>
	<?php endforeach; ?>
      </ul>
      <textarea class="textareaSousCommentaire" id="<?= $commentaire->getId() ?>" name="ajouterCommentaire" placeholder="Ecrire un sous commentaire..." maxlength="50"></textarea>
    </li>

    <?php endforeach; ?>
  </ul>
  
  <textarea id="textareaCommentaire" name="ajouterCommentaire" placeholder="Ecrire un commentaire..." maxlength="80"></textarea>
</div>
<!-- FIN LISTE DES COMMENTAIRES -->

<!-- DIALOGUE AFFICHE POUR AJOUTER UN MODERATEUR -->
<div class="dialog" id="dialogAjoutModerateurSondage" title="Ajouter un modérateur">
        <form id="formAjoutModerateurSondage" action="<?= ABSOLUTE_ROOT . '/index.php?controller=Sondage&action=ajouterModerateur' ?>" method="post" >
                <div id="dialogErreur" class="erreurs"></div>
                <table>
                        <tbody>
                                <tr>
                                        <th><label for="nomModerateur">Nom du modérateur</label></th>
                                        <td><input type="text" name="nomModerateur" id="nomModerateur" required="required" value="<?php if(!empty($_POST["nomModerateur"])) echo $_POST["nomModerateur"]; ?>"/></td>
                                        <td><a href="#" id="lienAjoutModerateur">Ajouter</a></td>
                                </tr>
                                
                        </tbody>
        
                        <tbody>
                                <tr>
                                        <th>Modérateurs</th>
                                        <td>
                                        <ul id="listeModerateurSondageDialog" class="listeModerateurs">
                                                <?php if(!empty($FicheSondage->getArrayModerateurs())): ?>
                                                <?php foreach($FicheSondage->getArrayModerateurs() as $moderateur): ?>
                                                <li class="user<?= $moderateur->getId() ?>">
                                                        <?= $moderateur->getPrenom() . ' ' . $moderateur->getNom() ?>
                                                        <?php if($user->getId() == $FicheSondage->getAdministrateur() || $user->getAdministrateurSite() == 1): //Si l'utilisateur connecté est l'administrateur du groupe ou l'administrateur du site ?>
                                                        <span class="lienSupprimerModerateur"><a href="#" id="supprimerModerateur<?= $moderateur->getId() ?>" onclick="supprimerModerateurSondage(<?= $moderateur->getId() ?>, <?= $FicheSondage->getId() ?>)"> supprimer </a></span>
                                                        <?php endif; ?>
                                                </li>
                                                <li class="aucunModerateur" style="display: none;">Il n'y a aucun modérateur dans ce sondage</li>
                                                <?php endforeach; ?>
                                                <?php else: ?>
                                                <li class="aucunModerateur">Il n'y a aucun modérateur dans ce sondage</li>
                                                <?php endif; ?>
                                        </ul>
                                        </td>
                                </tr>
                                
                        </tbody>
                </table>
                
                <div class="dialogButtons">
                        <input type="button" name="boutonFermerAjoutModerateurSondage" value="Fermer" id="boutonFermerAjoutModerateurSondage" />
                </div>
                
        </form>	
</div>
<!-- Fin dialogue -->

