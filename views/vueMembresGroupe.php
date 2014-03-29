<?php $this->titre = $groupe->getNom(); ?>

<input id="idGroupe" type="hidden" value="<?= $groupe->getId() ?>" />

<?php if($user->getId() == $groupe->getAdministrateurId() || $user->getAdministrateurSite() == 1 || $groupe->isModerateur($user->getId())): //Si l'utilisateur connecté est l'administrateur du groupe ou l'administrateur du site ?>
<input id="canDelete" type="hidden" value="1" />
<?php endif; ?>

<!-- MENU DE NAVIGATION DANS LE GROUPE -->
<div id="navigationGroupe">
        <ul>
            <li <?php if($pageSelected=="mur") echo 'class="selected"'; ?>><a href="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=afficherGroupe&params=' . $groupe->getId() ?>">Accueil</a></li>
            <li <?php if($pageSelected=="membres") echo 'class="selected"'; ?>><a href="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=afficherMembresGroupe&params=' . $groupe->getId() ?>">Membres</a></li>
            <li <?php if($pageSelected=="sondages") echo 'class="selected"'; ?>><a href="<?= ABSOLUTE_ROOT . '/controllers/ControllerSondage.php?action=afficherSondagesGroupe&params=' . $groupe->getId() ?>">Sondages</a></li>
        </ul>
</div>
<!-- FIN AFFICHAGE NAVIGATION -->


<!--AFFICHAGE DE L'ADMINISTRATEUR DU GROUPE -->
<h4>Administrateur du groupe</h4>
<p> <?= $groupe->getAdministrateur()->getPrenom() . ' ' . $groupe->getAdministrateur()->getNom() ?></p>
<!-- FIN AFFICHAGE ADMINISTRATEUR -->


<!-- AFFICHAGE DE LA LISTE DES MODERATEURS DU GROUPE -->
<div id="divModerateursGroupe">
        <h4>Modérateurs</h4>
        <ul id="listeModerateursGroupe" class="listeModerateurs">
        <?php if(!empty($groupe->getArrayModerateurs())): ?>
                <?php foreach($groupe->getArrayModerateurs() as $moderateur): ?>
                <li class="user<?= $moderateur->getId() ?>">
                        <?= $moderateur->getPrenom() . ' ' . $moderateur->getNom() ?>
                
                        <?php if($user->getId() == $groupe->getAdministrateurId() || $user->getAdministrateurSite() == 1): //Si l'utilisateur connecté est l'administrateur du groupe ou l'administrateur du site ?>
                        <span class="lienSupprimerMembre"><a href="#" id="supprimerModerateur<?= $moderateur->getId() ?>" onclick="supprimerModerateurGroupe(<?= $moderateur->getId() ?>, <?= $groupe->getId() ?>)"> supprimer </a></span>
                        <?php endif; ?>
                </li>
                <li class="aucunModerateur" style="display: none;">Il n'y a aucun modérateur dans ce groupe</li>
                <?php endforeach; ?>
                <?php else: ?>
                <li class="aucunModerateur">Il n'y a aucun modérateur dans ce groupe</li>
                <?php endif; ?>
         </ul>
        <?php if($user->getId() == $groupe->getAdministrateurId() || $user->getAdministrateurSite() == 1): //Si l'utilisateur connecté est l'administrateur du groupe ou l'administrateur du site ?>
        <p class="lienAjoutGroupe"><a href="#" onclick="afficherDialogueAjoutModerateurGroupe()"> Ajouter un moderateur </a></p>

        <?php endif; ?>
</div>
<!-- FIN LISTE DES MODERATEURS -->


<!-- AFFICHAGE DE LA LISTE DES MEMBRES DU GROUPE -->
<div id="divMembresGroupe">
        <h4>Membres</h4>
        <ul id="listeMembresGroupe" class="listeMembres">
                <?php if(!empty($groupe->getArrayMembres())): ?>
                <?php foreach($groupe->getArrayMembres() as $membre): ?>
                <li class="user<?= $membre->getId() ?>">
                        <?= $membre->getPrenom() . ' ' . $membre->getNom() ?>
                        <?php if($user->getId() == $groupe->getAdministrateurId() || $user->getAdministrateurSite() == 1 || $groupe->isModerateur($user->getId())): //Si l'utilisateur connecté est l'administrateur du groupe ou l'administrateur du site ?>
                        <span class="lienSupprimerMembre"><a href="#" id="supprimerMembre<?= $membre->getId() ?>" onclick="supprimerMembreGroupe(<?= $membre->getId() ?>, <?= $groupe->getId() ?>)"> supprimer </a></span>
                        <?php endif; ?>
                </li>
                <li class="aucunMembre" style="display: none;">Il n'y a aucun membre dans ce groupe</li>
                <?php endforeach; ?>
                <?php else: ?>
                <li class="aucunMembre">Il n'y a aucun membre dans ce groupe</li>
                <?php endif; ?>
        </ul>

        
        <?php if($user->getId() == $groupe->getAdministrateurId() || $user->getAdministrateurSite() == 1 || $groupe->isModerateur($user->getId())): //Si l'utilisateur connecté est l'administrateur du groupe ou l'administrateur du site ?>
        <p class="lienAjoutGroupe"><a href="#" onclick="afficherDialogueAjoutMembreGroupe()" > Ajouter un membre </a></p>
        <?php endif; ?>
        
</div>
<!-- FIN LISTE DES MEMBRES -->

<!-- AFFICHAGE DE LA LISTE DES MEMBRES DU GROUPE EN ATTENTE -->
<div id="divMembresEnAttenteGroupe">
        <h4>Membres en attente d'approbation</h4>
        <ul id="listeMembresEnAttenteGroupe" class="listeMembresEnAttente">
                <?php if(!empty($groupe->getArrayMembresEnAttente())): ?>
                <?php foreach($groupe->getArrayMembresEnAttente() as $membre): ?>
                <li class="user<?= $membre->getId() ?>">
                        <?= $membre->getPrenom() . ' ' . $membre->getNom() ?>
                        
                        <?php if($user->getId() == $groupe->getAdministrateurId() || $user->getAdministrateurSite() == 1): //Si l'utilisateur connecté est l'administrateur du groupe ou l'administrateur du site ?>
                        <span class="lienAjouterMembre"><a href="#" id="ajouterMembre<?= $membre->getId() ?>" onclick="ajouterMembreGroupe(<?= $membre->getId() ?>, <?= $groupe->getId() ?>, '<?= $membre->getNom() . ' ' . $membre->getPrenom()  ?>')"> accepter </a></span>
                        <span class="lienSupprimerMembre"><a href="#" id="supprimerMembre<?= $membre->getId() ?>" onclick="supprimerMembreGroupe(<?= $membre->getId() ?>, <?= $groupe->getId() ?>)"> refuser </a></span>
                        <?php endif; ?>
                </li>
                <li class="aucunMembreEnAttente" style="display: none;">Il n'y a aucun membre en attente dans ce groupe</li>
                <?php endforeach; ?>
                <?php else: ?>
                <li class="aucunMembreEnAttente">Il n'y a aucun membre en attente dans ce groupe</li>
                <?php endif; ?>
        </ul>        
</div>
<!-- FIN LISTE DES MEMBRES EN ATTENTE -->


<!-- DIALOGUE AFFICHE POUR AJOUTER UN MEMBRE -->
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
                                                <li class="user<?= $membre->getId() ?>">
                                                        <?= $membre->getPrenom() . ' ' . $membre->getNom() ?>
                                                        <?php if($user->getId() == $groupe->getAdministrateurId() || $user->getAdministrateurSite() == 1): //Si l'utilisateur connecté est l'administrateur du groupe ou l'administrateur du site ?>
                                                        <span class="lienSupprimerMembre"><a href="#" id="supprimerMembre<?= $membre->getId() ?>" onclick="supprimerMembreGroupe(<?= $membre->getId() ?>, <?= $groupe->getId() ?>)"> supprimer </a></span>
                                                        <?php endif; ?>
                                                </li>
                                                <li class="aucunMembre" style="display: none;">Il n'y a aucun membre dans ce groupe</li>
                                                <?php endforeach; ?>
                                                <?php else: ?>
                                                <li class="aucunMembre">Il n'y a aucun membre dans ce groupe</li>
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


<!-- DIALOGUE AFFICHE POUR AJOUTER UN MODERATEUR -->
<div class="dialog" id="dialogAjoutModerateurGroupe" title="Ajouter un modérateur">
        <form id="formAjoutModerateurGroupe" action="<?= ABSOLUTE_ROOT . '/index.php?controller=Groupe&action=ajouterModerateur' ?>" method="post" >
                <div id="dialogErreur" class="erreurs"></div>
                <table>
                        <tbody>
                                <tr>
                                        <th><label for="nomModerateur">Nom du modérateur</label></th>
                                        <td><input type="text" name="nomModerateur" id="nomModerateur" required="required" value="<?php if(!empty($_POST["nomModerateur"])) echo $_POST["nomModerateur"]; ?>"/></td>
                                        <td><a href="#" id="lienAjoutModerateur">Ajouter</a></td>
                                </tr>
                                
                        </tbody>
        
                        <tbody>
                                <tr>
                                        <th>Modérateurs</th>
                                        <td>
                                        <ul id="listeModerateurGroupeDialog" class="listeModerateurs">
                                                <?php if(!empty($groupe->getArrayModerateurs())): ?>
                                                <?php foreach($groupe->getArrayModerateurs() as $moderateur): ?>
                                                <li class="user<?= $moderateur->getId() ?>">
                                                        <?= $moderateur->getPrenom() . ' ' . $moderateur->getNom() ?>
                                                        <?php if($user->getId() == $groupe->getAdministrateurId() || $user->getAdministrateurSite() == 1): //Si l'utilisateur connecté est l'administrateur du groupe ou l'administrateur du site ?>
                                                        <span class="lienSupprimerModerateur"><a href="#" id="supprimerModerateur<?= $moderateur->getId() ?>" onclick="supprimerModerateurGroupe(<?= $moderateur->getId() ?>, <?= $groupe->getId() ?>)"> supprimer </a></span>
                                                        <?php endif; ?>
                                                </li>
                                                <li class="aucunModerateur" style="display: none;">Il n'y a aucun modérateur dans ce groupe</li>
                                                <?php endforeach; ?>
                                                <?php else: ?>
                                                <li class="aucunModerateur">Il n'y a aucun modérateur dans ce groupe</li>
                                                <?php endif; ?>
                                        </ul>
                                        </td>
                                </tr>
                                
                        </tbody>
                </table>
                
                <div class="dialogButtons">
                        <input type="button" name="boutonFermerAjoutModerateurGroupe" value="Fermer" id="boutonFermerAjoutModerateurGroupe" />
                </div>
                
        </form>	
</div>