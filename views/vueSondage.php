<?php $this->titre = $FicheSondage->getTitre(); 
?>
<input id="idSondage" type="hidden" value="<?= $FicheSondage->getId() ?>" />

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

<div id="containerCommentaires">
	<ul id="listeCommentaires">
		<?php foreach($listeCommentaires as $key=>$commentaire): ?>
		<li class="commentaire" id="commentaire<?= $commentaire->getId() ?>">
			<p class="pseudoCommentaire"><?= $commentaire->getUser()->getPrenom() . ' ' . $commentaire->getUser()->getNom() ?></p>
			<span class="texteCommentaire"><?= $commentaire->getTexte() ?></span>
			
			<span class="blocSoutiens"> 
				<span id="soutien<?=$commentaire->getId() ?>" ><?= $commentaire->getSoutiens() ?> </span>
				<span> <img src="<?= ABSOLUTE_ROOT . '/public/img/facebook-like-icon.png' ?>" onclick="ajouterSoutien( <?=$commentaire->getId() ?>)" /> </span>
			</span>
			
			<ul class="listeSousCommentaires">
				<?php foreach($commentaire->getSousCommentaires() as $key=>$sousCommentaire): ?>
				<li class="sousCommentaire" id="sousCommentaire<?= $sousCommentaire->getId() ?>">
				<span class="pseudoCommentaire"><?= $sousCommentaire->getUser()->getPrenom() . ' ' . $sousCommentaire->getUser()->getNom() ?></span> - <?= $sousCommentaire->getTexte() ?></li>
				<?php endforeach; ?>
			</ul>
			<textarea class="textareaSousCommentaire" id="<?= $commentaire->getId() ?>" name="ajouterCommentaire" placeholder="Ecrire un sous commentaire..." maxlength="50"></textarea>
		</li>

		<?php endforeach; ?>
	</ul>
	
	<textarea id="textareaCommentaire" name="ajouterCommentaire" placeholder="Ecrire un commentaire..." maxlength="80"></textarea>
</div>
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
