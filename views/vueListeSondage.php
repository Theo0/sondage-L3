<?php $this->titre = "Sondages" ?>
<div id="navigationListeGroupes">
        <ul>
                <li <?php if($pageSelected=="public") echo 'class="selected"'; ?>><a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerSondage.php?action=afficherSondagesPublic' ?>">Sondages publics</a></li>
                <?php if(!empty($_SESSION["id"])): ?>
                <li <?php if($pageSelected=="sondageInscrit") echo 'class="selected"'; ?>><a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerSondage.php?action=afficherSondagesInscrit' ?>">Sondages des inscrits</a></li>
                <li <?php if($pageSelected=="sondagePrive") echo 'class="selected"'; ?>><a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerSondage.php?action=afficherSondagesPrive' ?>">Sondages privés</a></li>
                <li <?php if($pageSelected=="sondageAdministre") echo 'class="selected"'; ?>><a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerSondage.php?action=afficherSondagesAdmin' ?>">Vos Sondages</a></li>
                <li <?php if($pageSelected=="sondageComplet") echo 'class="selected"'; ?>><a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerSondage.php?action=afficherSondagesComplet' ?>">Complétés</a></li>
                <?php endif; ?>
        </ul>
</div>

<?php if(empty($ListeSondage)): ?>
<p>Il n'y a aucun sondage</p>
<?php else: ?>
<table id="tableListeGroupes">
	<thead>
		<th>Titre</th>
		<th>Description</th>
		<th>Date de fin</th>
	</thead>
	<tbody>
		<?php foreach($ListeSondage as $key=>$sondage){ 
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
			<?php
			if($sondage->getVisibilite() == 'privé' && $sondage->getAdministrateur() == $_SESSION['id'])
			{
			?>
			<td><a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerSondage.php?action=afficheAjoutUserSondage&params=' . $sondage->getId() ?>">Ajouter des utilisateurs</a></td>
			<?php } ?>
			<?php
			if($admin == 1)
			{
			?>
			<td><a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerSondage.php?action=supprimerSondage&params=' . $sondage->getId() ?>"><img src="<?= ABSOLUTE_ROOT . '/public/css/images/red-cross.png' ?>"></a></td>
			<?php } ?>

		</tr>
		<?php } ?>
	</tbody>
</table>
<?php endif; ?>
