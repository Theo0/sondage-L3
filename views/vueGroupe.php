<?php $this->titre = $groupe->getNom(); ?>

<div id="navigationGroupe">
        <ul>
            <li <?php if($pageSelected=="mur") echo 'class="selected"'; ?>><a href="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=afficherGroupe&params=' . $groupe->getId() ?>">Accueil</a></li>
            <li <?php if($pageSelected=="membres") echo 'class="selected"'; ?>><a href="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=afficherMembresGroupe&params=' . $groupe->getId() ?>">Membres</a></li>
            <li <?php if($pageSelected=="sondages") echo 'class="selected"'; ?>><a href="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=afficherListeGroupesUser' ?>">Sondages</a></li>
        </ul>
</div>


<!--AFFICHAGE DES SOUS GROUPES -->
<h4>Sous groupes</h4>
<ul id="listeSousGroupes">
 

</ul>

<?php if($user->getId() == $groupe->getAdministrateurId() || $user->getAdministrateurSite() == 1 || $groupe->isModerateur($user->getId())): //Si l'utilisateur connecté est l'administrateur du groupe ou l'administrateur du site ?>
<form id="ajoutSousGroupe" action="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=ajouterSousGroupe' ?>">
    <input type="text" placeholder="Nom du sous groupe" name="nom" id="nomSousGroupe" />
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