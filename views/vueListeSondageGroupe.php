<?php $this->titre = "Sondages du groupe"; ?>

<div id="navigationGroupe">
        <ul>
            <li <?php if($pageSelected=="mur") echo 'class="selected"'; ?>><a href="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=afficherGroupe&params=' . $_GET['params'] ?>">Accueil</a></li>
            <li <?php if($pageSelected=="membres") echo 'class="selected"'; ?>><a href="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=afficherMembresGroupe&params=' . $_GET['params'] ?>">Membres</a></li>
            <li <?php if($pageSelected=="sondages") echo 'class="selected"'; ?>><a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerSondage.php?action=afficherSondagesGroupe&params=' .  $_GET['params'] ?>">Sondages</a></li>
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
		<?php foreach($ListeSondage as $key=>$sondage){ ?>
		<tr class="<?php if($key%2) echo 'ligneImpaire'; else echo 'lignePaire'; ?>">
			<td><a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerSondage.php?action=afficherFicheSondage&params=' . $sondage->getId() ?>"><?php echo($sondage->getTitre()); ?></a></td>
			<td><a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerSondage.php?action=afficherFicheSondage&params=' . $sondage->getId() ?>"><?php echo($sondage->getDesc()); ?></a></td>
			<td><a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerSondage.php?action=afficherFicheSondage&params=' . $sondage->getId() ?>"><?php echo($sondage->getDateFin()); ?></a></td>
			<?php
			if($sondage->getVisibilite() == 'privé' && $sondage->getAdministrateur() == $_SESSION['id'])
			{
			?>
			<td><a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerSondage.php?action=afficheAjoutUserSondage&params=' . $sondage->getId() ?>">Ajouter des utilisateurs</a></td>
			<?php } ?>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?php endif; ?>

