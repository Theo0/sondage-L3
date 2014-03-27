<?php 

$this->titre = "Ajout d'une option";

if(isset($optionTermine)){
	echo "<h3>L'option a bien été ajoutée ! Vous pouvez maintenant en ajouter une nouvelle.</h3>";
}
?> 

<div>

<form method="POST" action="http://localhost/sondage-L3/controllers/ControllerSondage.php?action=nouvelleOption" id="formulaire_nouv_opt" >
<p><label>Option :   </label><input name="texte" type="text" required="required" /></p>
<p><label>Sondage :   </label><select name="id_sondage">
<?php foreach($ListeSondage as $key=>$sondage){ ?>
<option value="<?php echo($sondage->getId());?>"><?php echo($sondage->getTitre()); ?></option>
<?php } ?>
</select></p>
<p><input name="submit" type="submit" /></p>
</form>  
</div>
