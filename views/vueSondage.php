<?php $this->titre = $FicheSondage->getTitre(); ?>

<input id="idSondage" type="hidden" value="<?= $FicheSondage->getId() ?>" />

<div id="ficheSondage">
<?php if(empty($FicheSondage)): ?>
<?php $this->titre = "Sondage non trouvé"; ?>
<p>Erreur : Aucun sondage séléctionné !</p>
<?php else: ?>
<?php $this->titre = $FicheSondage->getTitre(); ?>
<h1><?php
echo($FicheSondage->getTitre());
 ?></h1>
 <br />
 <h3><?php
echo($FicheSondage->getDesc());
 ?></h3>
 <br />
<p>Ordonnez vos réponses parmis les choix ci-dessous : </p>
<table id="tableListeSondages">
	<thead>
		<th>Option</th>
	</thead>
	<tbody>
		<?php foreach($ListeOptions as $key=>$option){ ?>
		<tr class="<?php if($key%2) echo 'ligneImpaire'; else echo 'lignePaire'; ?>">
			<td><?php echo($option->getTexte()); ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>

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
<?php endif; ?>
</div>
