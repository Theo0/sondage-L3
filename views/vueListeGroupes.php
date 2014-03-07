<?php $this->titre = "Groupes" ?>

<div id="navigationListeGroupes">
        <ul>
                <li <?php if($pageSelected=="public") echo 'class="selected"'; ?>><a href="http://localhost/sondage-L3/index.php">Groupes publics</a></li>
                <li <?php if($pageSelected=="groupesUser") echo 'class="selected"'; ?>><a href="http://localhost/sondage-L3/index.php?controller=Groupe&amp;action=afficherListeGroupesPublic">Vos groupes</a></li>
                <li <?php if($pageSelected=="groupesAdministre") echo 'class="selected"'; ?>><a href="http://localhost/sondage-L3/index.php?controller=Groupe&amp;action=afficherListeGroupesPublic">Groupes que vous administrez</a></li>
        </ul>
</div>

<?php if(empty($listeGroupes)): ?>
<p>Il n'y a aucun groupe</p>
<?php else: ?>
<table id="tableListeGroupes">
    <thead> <!-- En-tête du tableau -->
        <tr>
            <th>Nom</th>
            <th>Rejoindre</th>
        </tr>
    </thead>
    <tbody> <!-- Corps du tableau -->
        <?php foreach($listeGroupes as $key=>$groupe): ?>
         <tr>
            <td><?= $groupe->getNom() ?></td>
            <td><a href="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=rejoindreGroupe&params=' . $groupe->getId() ?>">Rejoindre</a></td>
        </tr>   
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>