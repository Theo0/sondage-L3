<?php $this->titre = "Accueil"; ?>

<br />
<h1 style="color : black;">Derniers Sondages Ajoutés</h1><br /><br />
 <table id="tableListeGroupes">
	<thead>
		<th>Titre</th>
		<th>Description</th>
		<th>Date de fin</th>
	</thead>
	<tbody>
		<?php 
		$i = 0;
		foreach($ListeSondage as $key=>$sondage){
			$i = $i+1;
			if($i > 10){
				break;
			} 
			$datefin = $sondage->getDateFin(); 
			$now = date('Y-m-d H:i:s');
			$now = new DateTime( $now );
			$now = $now->format('Y-m-d H:i:s');
			$datefin = new DateTime($datefin);
			$datefin = $datefin->format('Y-m-d H:i:s');
			?>
		<tr class="<?php if($key%2) echo 'ligneImpaire'; else echo 'lignePaire'; ?>">
			<?php 
			if($now<$datefin){
				?>
				<td><a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerSondage.php?action=afficherFicheSondage&params=' . $sondage->getId() ?>"><?php echo($sondage->getTitre()); ?></a></td>
				<td><a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerSondage.php?action=afficherFicheSondage&params=' . $sondage->getId() ?>"><?php echo($sondage->getDesc()); ?></a></td>
				<td><a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerSondage.php?action=afficherFicheSondage&params=' . $sondage->getId() ?>">
				<?php
				echo $datefin . "</a></td>";
			}
			else{
				?>
				<td><a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerSondage.php?action=resultat&params=' . $sondage->getId() ?>"><?php echo($sondage->getTitre()); ?></a></td>
				<td><a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerSondage.php?action=resultat&params=' . $sondage->getId() ?>"><?php echo($sondage->getDesc()); ?></a></td>
				<td><a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerSondage.php?action=resultat&params=' . $sondage->getId() ?>">
				<?php
				echo "Terminé". "</a></td>";
			}
			?></a></td>
		</tr>
		<?php } ?>
	</tbody>
</table>


