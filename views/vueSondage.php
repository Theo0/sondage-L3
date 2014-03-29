<?php $this->titre = $FicheSondage->getTitre(); 
?>
<input id="idSondage" type="hidden" value="<?= $FicheSondage->getId() ?>" />

<div id="ficheSondage">
<?php if(empty($FicheSondage)){ ?>
<?php $this->titre = "Sondage non trouvé"; ?>
<p>Erreur : Aucun sondage séléctionné !</p>
<?php } else{ ?>
<?php $this->titre = $FicheSondage->getTitre(); ?>
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
<?php foreach($ListeOptions as $key=>$option){ ?>
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
<br />
<?php } ?>
<input type="submit" value="Voter">
</form>

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
<?php }
else{
	echo "Vous avez déjà répondu à ce sondage !";
} 
}
?>
</div>
