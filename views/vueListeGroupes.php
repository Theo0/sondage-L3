<?php $this->titre = "Groupes" ?>

<div id="navigationListeGroupes">
        <ul>
                <li <?php if($pageSelected=="public") echo 'class="selected"'; ?>><a href="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=afficherListeGroupesPublic' ?>">Groupes publics</a></li>
                <li <?php if($pageSelected=="privé_visible") echo 'class="selected"'; ?>><a href="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=afficherListeGroupesPrivesVisibles' ?>">Groupes privés</a></li>
                <?php if(!empty($_SESSION["id"])): ?>
                <li <?php if($pageSelected=="groupesUser") echo 'class="selected"'; ?>><a href="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=afficherListeGroupesUser' ?>">Vos groupes</a></li>
                <li <?php if($pageSelected=="groupesAdministre") echo 'class="selected"'; ?>><a href="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=afficherListeGroupesAdministres' ?>">Groupes que vous administrez</a></li>
                <?php endif; ?>
        </ul>
</div>

<br/>

<?php if(empty($listeGroupes)): ?>
<p>Il n'y a aucun groupe</p>
<?php else: ?>
<table id="tableListeGroupes">
    <thead> <!-- En-tête du tableau -->
        <tr>
            <th>Nom</th>
            <th id="columnRejoindre"></th>
        </tr>
    </thead>
    <tbody> <!-- Corps du tableau -->
        <?php foreach($listeGroupes as $key=>$groupe): ?>
        <?php
                $isMemberOrModerator = false;
                foreach($groupe->getArrayMembres() as $membre){
                        if(!empty($_SESSION["id"]) && $membre->getId() == $_SESSION["id"]){
                                $isMemberOrModerator = true;
                                break;
                        }
                }
                foreach($groupe->getArrayModerateurs() as $moderateur){
                        if(!empty($_SESSION["id"]) && $moderateur->getId() == $_SESSION["id"]){
                                $isMemberOrModerator = true;
                                break;
                        }
                }
                
                $isAdminGroupe = false;
                if($groupe->getAdministrateurId() == $_SESSION["id"])
                        $isAdminGroupe = true;
        ?>
         <tr class="<?php if($key%2) echo 'ligneImpaire'; else echo 'lignePaire'; ?>" >
            <td><a href="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=afficherGroupe&params=' . $groupe->getId() ?>"><?= $groupe->getNom() ?></a></td>
            <?php if($isMemberOrModerator): ?>
            <td><a href="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=quitterGroupe&params=' . $groupe->getId() ?>">Quitter le groupe</a></td>
            <?php elseif($isAdminGroupe): ?>
            <td><a href="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=supprimerGroupe&params=' . $groupe->getId() ?>">Supprimer le groupe</a></td>
            <?php elseif($pageSelected != "groupesUser" && $pageSelected != "groupesAdministre"): ?>
            <td><a href="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=rejoindreGroupe&params=' . $groupe->getId() ?>">Rejoindre</a></td>            
            <?php endif; ?>
        </tr>   
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>