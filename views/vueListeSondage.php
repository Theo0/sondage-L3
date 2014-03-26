<?php if(empty($ListeSondage)): ?>
<p>Il n'y a aucun groupe</p>
<?php else: ?>
<table id="tableListeSondages">
	<thead>
		<th>Titre</th>
		<th>Description</th>
		<th>Date de fin</th>
	</thead>
	<tbody>
		<?php foreach($ListeSondage as $key=>$sondage){ ?>
		<tr class="<?php if($key%2) echo 'ligneImpaire'; else echo 'lignePaire'; ?>">
			<td><?php echo($sondage->getTitre()); ?></td>
			<td><?php echo($sondage->getDesc()); ?></td>
			<td><?php echo($sondage->getDateFin()); ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?php endif; ?>
