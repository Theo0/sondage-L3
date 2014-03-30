<?php 

$this->titre = "Ajout d'une option";

if(isset($optionTermine)){
	echo "<h3>L'option a bien été ajoutée ! Vous pouvez maintenant en ajouter une nouvelle.</h3>";
}
?> 

<div id="CreationSondage">

<form id="formCreationSondage" method="POST" action="http://localhost/sondage-L3/controllers/ControllerSondage.php?action=nouvelleOption" id="formulaire_nouv_opt" >
<table>
	<tbody><tr>
		<th><label>Option</label></th><td><input name="texte" type="text" required="required" /></td>
	</tr></tbody>	
<tbody>
	<th><label>Sondage :   </label></th><td><select name="id_sondage">
	<?php foreach($ListeSondage as $key=>$sondage){ ?>
	<option value="<?php echo($sondage->getId());?>"><?php echo($sondage->getTitre()); ?></option>
	<?php } ?>
	</select></td>
</tbody>
</table>
<br /><br /><br />
<p><input name="submit" type="submit" value="Ajouter" /></p>
</form>  
</div>
