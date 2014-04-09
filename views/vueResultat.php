<style>.ui-progressbar {
    width: 70%;
  	}</style>
<?php 
require_once ROOT . "/models/Score.php";
$this->titre = "Résultat de " . $FicheSondage->getTitre(); 
?>

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
<?php if(empty($FicheSondage)){ ?>
<?php $this->titre = "Sondage non trouvé"; ?>
<p>Erreur : Aucun sondage séléctionné !</p>
<?php } else{ ?>
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

</div>
<?php } ?>