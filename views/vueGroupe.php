<?php $this->titre = $groupe->getNom(); ?>

<div id="navigationGroupe">
        <ul>
            <li <?php if($pageSelected=="mur") echo 'class="selected"'; ?>><a href="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=afficherGroupe&params=' . $groupe->getId() ?>">Accueil</a></li>
            <li <?php if($pageSelected=="membres") echo 'class="selected"'; ?>><a href="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=afficherMembres&params=' . $groupe->getId() ?>">Membres</a></li>
            <li <?php if($pageSelected=="sondages") echo 'class="selected"'; ?>><a href="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=afficherListeGroupesUser' ?>">Sondages</a></li>
        </ul>
</div>
