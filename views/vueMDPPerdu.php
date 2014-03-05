<?php $this->titre = "Mot de passe perdu" ?>

<p>Indiquez votre email ci-dessous puis validez</p>
<p>Vous recevrez dans quelques instant un email avec un lien vous permettant de réinitialiser votre mot de passe</p>

<form action="<?= ABSOLUTE_ROOT . "/controllers/ControllerUser.php?action=envoiEmailRecuperationMDP" ?>" method="post">
    <p>
        <label for="email"> Votre adresse email: </label>
        <input type="email" name="email" id="email" value="<?php if(!empty($_POST["email"])) echo $_POST["email"]; ?>" /> 
    </p>
    
    <p>
            <input type="submit" name="envoiEmailMDPPerdu" value="Valider" />
    </p>
</form>