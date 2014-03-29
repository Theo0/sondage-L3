<?php $this->titre = $groupe->getNom(); ?>

<div id="navigationGroupe">
        <ul>
            <li <?php if($pageSelected=="mur") echo 'class="selected"'; ?>><a href="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=afficherGroupe&params=' . $groupe->getId() ?>">Accueil</a></li>
            <li <?php if($pageSelected=="membres") echo 'class="selected"'; ?>><a href="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=afficherMembresGroupe&params=' . $groupe->getId() ?>">Membres</a></li>
            <li <?php if($pageSelected=="sondages") echo 'class="selected"'; ?>><a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerSondage.php?action=afficherSondagesGroupe&params=' . $groupe->getId() ?>">Sondages</a></li>
        </ul>
</div>


<!--AFFICHAGE DES SOUS GROUPES -->
<h4>Sous groupes</h4>
<ul id="listeSousGroupes">
<?php foreach($sousGroupes->getArrayGroupes() as $key=>$sousGroupe): ?>
<li id="sousGroupe<?= $sousGroupe->getId(); ?>"><?= $sousGroupe->getNom(); ?></li>
<?php endforeach; ?>
</ul>

<?php if($user->getId() == $groupe->getAdministrateurId() || $user->getAdministrateurSite() == 1 || $groupe->isModerateur($user->getId())): //Si l'utilisateur connecté est l'administrateur du groupe ou l'administrateur du site ?>
<form id="formCreationSousGroupe" action="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=ajouterSousGroupe' ?>" method="post">
    <input type="text" placeholder="Nom du sous groupe" name="nom" id="nomSousGroupe" />
    <input type="hidden" name="groupeId" value="<?= $groupe->getId() ?>" />
    <span class="lienAjoutSousGroupe"><input type="button" id="boutonAjoutGroupe" name="ajoutGroupe" value="Ajouter un sous groupe" /></span>
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