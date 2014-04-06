<style>.ui-progressbar {
    width: 70%;
  	}</style>
<?php $this->titre = "Résultat de " . $FicheSondage->getTitre(); 
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
   <?php
	echo "<br />";

}
?>


<div id="containerCommentaires">
	<ul id="listeCommentaires">
		<?php foreach($listeCommentaires as $key=>$commentaire): ?>
		<li id="commentaire<?= $commentaire->getId() ?>"><?= $commentaire->getTexte() ?></li>
		<?php endforeach; ?>
		<li id="creerCommentaire">
			<textarea id="textareaCommentaire" name="ajouterCommentaire" placeholder="Ecrire un commentaire..."></textarea>
		</li>
	</ul>
</div>

</div>
<?php } ?>