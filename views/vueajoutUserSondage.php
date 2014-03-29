<?php 

$this->titre = "Ajout d'un membre au sondage";

if(isset($membreTermine)){
	echo "<h3>Le membre a bien été ajoutée ! Vous pouvez maintenant en ajouter un nouveau.</h3>";
}
?> 

<div>
<p>Ajouter un membre au sondage <?php echo $NomSondage; ?>.</p>
<form method="POST" action="http://localhost/sondage-L3/controllers/ControllerSondage.php?action=ajoutUserSondage" id="formulaire_nouv_opt" >
<p><label>Utilisateur :   </label><select name="user_votant">
<?php foreach($ListeUser as $key=>$user){ ?>
<option value="<?php echo($user->getId());?>"><?php echo($user->getNom()); echo "  "; echo($user->getPrenom()); ?></option>
<?php } ?>
</select></p>
<input type="hidden" name="id" value="<?php echo $_GET['params']; ?>" />
<p><input name="submit" type="submit" /></p>
</form>  
</div>
