<?php 

$this->titre = "Ajout d'un membre au sondage";

if(isset($membreTermine)){
	echo "<h3>Le membre a bien été ajoutée ! Vous pouvez maintenant en ajouter un nouveau.</h3>";
}
?> 

<div id="CreationSondage">
<h4>Ajouter un membre au sondage <?php echo $NomSondage; ?>.</h4><br /><br />
<form id="formCreationSondage" method="POST" action="http://localhost/sondage-L3/controllers/ControllerSondage.php?action=ajoutUserSondage&params=<?=$idSondage;?>" id="formulaire_nouv_opt" >
<table>
	<tbody>
		<tr>
		<th><label>Utilisateur</label></th><td><select name="user_votant">
		<?php foreach($ListeUser as $key=>$user){ ?>
		<option value="<?php echo($user->getId());?>"><?php echo($user->getNom()); echo "  "; echo($user->getPrenom()); ?></option>
		<?php } ?>
		</select></td>

		<input type="hidden" name="id" value="<?php echo $_GET['params']; ?>" />
</tr></tbody>
</table>
<br /><br />
<p><input name="submit" type="submit" value="Ajouter"/></p>
</form>  
</div>
