<style>.ui-progressbar {
    width: 70%;
  	}</style>
<?php

require_once ROOT . "/models/Score.php";
$this->titre = "Résultat de " . $FicheSondage->getTitre(); 
?>

<?php if($user->getId() == $FicheSondage->getAdministrateur() || $user->getAdministrateurSite() == 1 || $FicheSondage->isModerateur($user->getId())): //Si l'utilisateur connecté est l'administrateur du groupe ou l'administrateur du site ?>
          <input id="canDelete" type="hidden" value="1" />
<?php endif; ?>
<input id="idSondage" type="hidden" value="<?= $FicheSondage->getId() ?>" />
<?php 
$total = 0;
  foreach($ListeOptions as $key=>$option){
    $idOpt = $option->getId();
    $total = $total + $tabResult[$idOpt]->getScore();
  }
  if($total==0){ // Pour eviter une eventuelle division par 0
	 $total = 1;
  }
$id = 1;
$op = 10000;
?>
<div id="ficheSondage">
    <?php if($FicheSondage->getId() == -1){ ?>
      <?php $this->titre = "Sondage non trouvé"; ?>
      <p>Erreur : Aucun sondage séléctionné !</p>
    <?php }
    if($FicheSondage->getId() != -1): ?>
            <h1><?php
            echo "Résultat de ";
            echo($FicheSondage->getTitre());
             ?></h1>
             <br />
             <h3><?php
            echo($FicheSondage->getDesc());
             ?></h3>
             <br />

            <?php foreach($ListeOptions as $key=>$option){
            	$id = $id+1;
            	$op = $op+1;

            	echo($option->getTexte());
            	$idOpt = $option->getId();
            	$pourcent = round(($tabResult[$idOpt]->getScore() * 100) / $total);
            	echo "  : " . $pourcent . "%";
            	?>
            	
            	<script>
              	$(function() {
                $( '<?php echo "#". $option->getId(); ?>' ).progressbar({
                  value: <?= $pourcent; ?>
                	});
             	 });
              	</script>
               <div id='<?php echo($option->getId());?>'></div>
               <?php if($FicheSondage->getSecret() != "secret"):?>
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
             
            <button id='<?=$op;?>'>Afficher les votants</button>
               
               
               

               <?php
               endif;
            	echo "<br /><br /><br />";

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
        <?php if($user->getId() != -1): ?>
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
                
        	<?php if($user->getId() == $FicheSondage->getAdministrateur() || $user->getAdministrateurSite() == 1): //Si l'utilisateur connecté est l'administrateur du groupe ou l'administrateur du site ?>
        	<span><a href="#" onclick="supprimerCommentaire(<?= $sousCommentaire->getId() ?>)"><img src="http://localhost/sondage-L3/public/css/images/red-cross.png"> </a></span>
        	<?php endif; ?>
        	</li>
        	<?php endforeach; ?>
              </ul>
              <textarea class="textareaSousCommentaire" id="<?= $commentaire->getId() ?>" name="ajouterCommentaire" placeholder="Ecrire un sous commentaire..." maxlength="50"></textarea>
            </li>

            <?php endforeach; ?>
          </ul>
          
          <textarea id="textareaCommentaire" name="ajouterCommentaire" placeholder="Ecrire un commentaire..." maxlength="80"></textarea>
        </div>
        <?php endif; ?>
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
<?php 
endif;
?>