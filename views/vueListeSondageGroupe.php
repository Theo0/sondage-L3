<?php $this->titre = "Sondages du groupe"; ?>

<div id="navigationGroupe">
        <ul>
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
<br />
<a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerSondage.php?action=afficherNouveauSondage&params=' . $_GET['params'] ?>" ><img src="<?= ABSOLUTE_ROOT . '/public/css/images/cross.png' ?>"> Ajouter un sondage à ce groupe</a>
<br /><br /><br />
<!--AFFICHAGE DES SOUS GROUPES -->
<h4>Sous groupes</h4>
<ul id="listeSousGroupes">
<?php foreach($sousGroupes->getArrayGroupes() as $key=>$sousGroupe): ?>
<li id="sousGroupe<?= $sousGroupe->getId(); ?>"><?= $sousGroupe->getNom(); ?></li>

<!-- AFFICHAGE DE LA LISTE DES SONDAGES DU SOUS GROUPE -->
<?php 

if(isset($ListeSondagesSous[$sousGroupe->getId()])){
$ListeSondages = $ListeSondagesSous[$sousGroupe->getId()] -> getArraySondage();	
?>
<table id="tableListeGroupes">
	<thead>
		<th>Titre</th>
		<th>Description</th>
		<th>Date de fin</th>
	</thead>
	<tbody>
		<?php foreach($ListeSondages as $key=>$sondage){ ?>
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
<?php
}
?>
<!-- FIN AFFICHAGE DE LA LISTE DES SONDAGES DU SOUS GROUPE -->
<br />
<a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerSondage.php?action=afficherNouveauSondage&params=' . $_GET['params'] . '&param2=' . $sousGroupe->getId() ?>" ><img src="<?= ABSOLUTE_ROOT . '/public/css/images/cross.png' ?>"> Ajouter un sondage à ce sous-groupe</a>
<?php endforeach; ?>
</ul>

<?php if($user->getId() == $groupe->getAdministrateurId() || $user->getAdministrateurSite() == 1 || $groupe->isModerateur($user->getId())): //Si l'utilisateur connecté est l'administrateur du groupe ou l'administrateur du site ?>
<form id="formCreationSousGroupe" action="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=ajouterSousGroupe&params=' . $groupe->getId() ?>" method="post">
    <input type="text" placeholder="Nom du sous groupe" name="nom" id="nomSousGroupe" />
    <input type="hidden" name="groupeId" value="<?= $groupe->getId() ?>" />
    <span class="lienAjoutSousGroupe"><input type="button" id="boutonAjoutGroupe" name="ajoutGroupe" value="Créer" /></span>
</form>
<?php endif; ?>   
<!--FIN AFFICHAGE DES SOUS GROUPES -->

<script>
    $( document ).ready(function(){
        $("#boutonAjoutGroupe").click(function(){
            ajouterSousGroupe($("#nomSousGroupe").val(), <?= $groupe->getId() ?>);
        });
    });
</script>