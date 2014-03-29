
<?php $this->titre = $FicheSondage->getTitre(); ?>
<div id="ficheSondage">
<?php if(empty($FicheSondage)): ?>
<p>Erreur : Aucun sondage séléctionné !</p>
<?php else: ?>
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
<?php endif; ?>
</div>
