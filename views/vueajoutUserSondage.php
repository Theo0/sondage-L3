<?php 

$this->titre = "Ajout d'un membre au sondage";

if(isset($optionTermine)){
	echo "<h3>Le membre a bien été ajoutée ! Vous pouvez maintenant en ajouter un nouveau.</h3>";
}
?> 

<div>


<form method="POST" action="http://localhost/sondage-L3/controllers/ControllerSondage.php?action=ajoutUserSondage&params=" id="formulaire_nouv_opt" >
<p><label>Utilisateur :   </label><select name="id_user">
<?php foreach($ListeUser as $key=>$user){ ?>
<option value="<?php echo($user->getId());?>"><?php echo($user->getNom()); echo "  "; echo($user->getPrenom()); ?></option>
<?php } ?>
</select></p>
<input type="hidden" value="<?php echo $_GET['params']; ?>" />
<p><input name="submit" type="submit" /></p>
</form>  
</div>
