<?php $this->titre = "Connecté!"; ?>

<p> Utilisateur connecté : <?= $_SESSION["id"] ?> </p>
<?php header("location:" . ABSOLUTE_ROOT);