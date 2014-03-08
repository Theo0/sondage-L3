<?php $this->titre = $groupe->getNom(); ?>

<input id="idGroupe" type="hidden" value="<?= $groupe->getId() ?>" />

<div id="navigationGroupe">
        <ul>
            <li <?php if($pageSelected=="mur") echo 'class="selected"'; ?>><a href="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=afficherGroupe&params=' . $groupe->getId() ?>">Accueil</a></li>
            <li <?php if($pageSelected=="membres") echo 'class="selected"'; ?>><a href="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=afficherMembresGroupe&params=' . $groupe->getId() ?>">Membres</a></li>
            <li <?php if($pageSelected=="sondages") echo 'class="selected"'; ?>><a href="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=afficherListeGroupesUser' ?>">Sondages</a></li>
        </ul>
</div>

<h4>Administrateur du groupe</h4>
<p> <?= $groupe->getAdministrateur()->getPrenom() . ' ' . $groupe->getAdministrateur()->getNom() ?></p>

<div id="divModerateursGroupe">
        <h4>Modérateurs</h4>
        <?php if(!empty($groupe->getArrayModerateurs())): ?>
        <ul id="listeModerateursGroupe">
                <?php foreach($groupe->getArrayModerateurs() as $moderateur): ?>
                <li><?= $moderateur->getPrenom() . ' ' . $moderateur->getNom() ?></li>
                <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <p>Il n'y a aucun modérateur dans ce groupe</p>
        <?php endif; ?>
        
        <?php if($user->getId() == $groupe->getAdministrateurId() || $user->getAdministrateurSite() == 1): //Si l'utilisateur connecté est l'administrateur du groupe ou l'administrateur du site ?>
        <p class="lienAjoutGroupe"><a href="#" id="lienAjoutModerateur"> Ajouter un moderateur </a></p>

        <?php endif; ?>
</div>

<div id="divMembresGroupe">
        <h4>Membres</h4>
        <ul id="listeMembresGroupe" class="listeMembres">
                <?php if(!empty($groupe->getArrayMembres())): ?>
                <?php foreach($groupe->getArrayMembres() as $membre): ?>
                <li><?= $membre->getPrenom() . ' ' . $membre->getNom() ?></li>
                <?php endforeach; ?>
                <?php else: ?>
                <li id="aucunMembre">Il n'y a aucun membre dans ce groupe</li>
                <?php endif; ?>
        </ul>

        
        <?php if($user->getId() == $groupe->getAdministrateurId() || $user->getAdministrateurSite() == 1): //Si l'utilisateur connecté est l'administrateur du groupe ou l'administrateur du site ?>
        <p class="lienAjoutGroupe"><a href="#" onclick="afficherDialogueAjoutMembreGroupe()" > Ajouter un membre </a></p>
        <?php endif; ?>
        
</div>



<div class="dialog" id="dialogAjoutMembreGroupe" title="Ajouter un membre">
        <form id="formAjoutMembreGroupe" action="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=ajouterMembre' ?>" method="post" >
                <div id="dialogErreur" class="erreurs"></div>
                <table>
                        <tbody>
                                <tr>
                                        <th><label for="nomMembre">Nom du membre</label></th>
                                        <td><input type="text" name="nomMembre" id="nomMembre" required="required" value="<?php if(!empty($_POST["nomMembre"])) echo $_POST["nomMembre"]; ?>"/></td>
                                        <td><a href="#" id="lienAjoutMembre">Ajouter</a></td>
                                </tr>
                                
                        </tbody>
        
                        <tbody>
                                <tr>
                                        <th>Membres</th>
                                        <td>
                                        <ul id="listeMembresGroupeDialog" class="listeMembres">
                                                <?php if(!empty($groupe->getArrayMembres())): ?>
                                                <?php foreach($groupe->getArrayMembres() as $membre): ?>
                                                <li><?= $membre->getPrenom() . ' ' . $membre->getNom() ?></li>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                        </ul>
                                        </td>
                                </tr>
                                
                        </tbody>
                </table>
                
                <div class="dialogButtons">
                        <input type="button" name="boutonFermerAjoutMembreGroupe" value="Fermer" id="boutonFermerAjoutMembreGroupe" />
                </div>
                
        </form>	
</div>