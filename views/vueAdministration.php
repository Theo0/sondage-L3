<?php $this->titre="administration" ?>



<h2>Gestion des inscriptions</h2>
<form action="<?= ABSOLUTE_ROOT . '/index.php?controller=Admin&action=modifierInscription' ?>" method="POST">
    <p>
        <input type="radio" name="inscription" value="ouvertes" <?php if($inscriptions=="ouvertes") echo 'checked="checked"'; ?> > Ouvertes
    </p>
    <p>
        <input type="radio" name="inscription" value="validation" <?php if($inscriptions=="validation") echo 'checked="checked"'; ?> > Avec validation
    </p>
    <input type="submit" name="modifierInscription" value="Modifier" />
</form>

<h2>Gestion des utilisateurs</h2>
<table>
    <thead> <!-- En-tête du tableau -->
        <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Pseudo</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($listeUsers->getArrayUser() as $key=>$user): ?>
        <tr>
            <td><?= $user->getId() ?></td>
            <td><?= $user->getNom() . ' ' . $user->getPrenom() ?></td>
            <td><?= $user->getpseudo() ?></td>
            <?php if($user->getCompteValide()): ?>
                <td><a href="<?= ABSOLUTE_ROOT . '/index.php?controller=User&action=bannirMembre&params=' . $user->getId() ?>">Bannir</a></td>
            <?php else: ?>
                <td><a href="<?= ABSOLUTE_ROOT . '/index.php?controller=User&action=activerMembre&params=' . $user->getId() ?>">Activer</a></td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>