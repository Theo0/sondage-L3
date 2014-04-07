<?php 

$this->titre = "Nouveau Sondage";

?> 

<script type="text/javascript">
            var intTextBox=0;
            //Ajouter une textbox
            function addElement()
          {
            intTextBox = intTextBox + 1;
            var contentID = document.getElementById('optionsdyn');
            var newTBDiv = document.createElement('div');
                newTBDiv.setAttribute('id','strText'+intTextBox);
            newTBDiv.innerHTML = "<label>Option "+intTextBox+" 	  : </label><input type='text' id='" + intTextBox + "'    name='" + intTextBox + "' required/>";
            contentID.appendChild(newTBDiv);
          }
            //Supprimer la derniere textbox
            function removeElement()
            {
              if(intTextBox != 0)
              {
                    var contentID = document.getElementById('optionsdyn');
                    contentID.removeChild(document.getElementById('strText'+intTextBox));
                    intTextBox = intTextBox-1;
                }
            }
</script>

<div id="CreationSondage">

<form id="form1" method="POST" action="http://localhost/sondage-L3/controllers/ControllerSondage.php?action=nouveauSondage" >
<table>
	<tbody><tr>
		<th><label for="TitreSondage">Titre du sondage</label></th><td><input id="id_titre" name="titre" type="text" required="required" /></td>
	</tr></tbody>

	<tbody><tr>
		<th><label for="DescriptionSondage">Description</label></th><td><input id="id_description" name="description" type="text" required="required"  /></td>
	</tr></tbody>

	<tbody><tr>
		<th><label for="DateSondage">Date de fin</label></th><td><input name="date_fin" type="date" /></td>
	</tr></tbody>	
	<tbody>

	<tbody>
		<tr>
			<th>Visibilité</th>
			<td>
				<ul>
					<?php if(!isset($_GET['params'])): ?>
					<li class="bottomSeparator">
						<div class="visibiliteRadioInput">
							<label for="visibilitePublic">Public</label>
							<input id="visibilitePublic" name="visibilite" type="radio" required="required" value="public" checked />
							<p>N'importe qui peut voir et voter dans ce sondage, même sans inscription.</p>
						</div>
					</li>
					<li class="bottomSeparator">
						<div class="visibiliteRadioInput">
							<label for="visibiliteInscrits">Inscrits</label>
							<input id="visibiliteInscrits" name="visibilite" type="radio" required="required" value="inscrits" />
							<p>Une inscription est nécéssaire pour voir et voter dans ce sondage.</p>
						</div>
					</li>	
					<li class="bottomSeparator">
						<div class="visibiliteRadioInput">
							<label for="visibilitePrive">Privé</label>
							<input id="visibilitePrive" name="visibilite" type="radio" required="required" value="prive" />
							<p>Par défaut, personne ne voit ce sondage. Vous pouvez ensuite ajouter les membres que vous souhaitez à ce sondage.</p>
						</div>
					</li>
					<?php
					endif;
					if(isset($_GET['params'])):
					 ?>
					<li class="bottomSeparator">
						<div class="visibiliteRadioInput">
							<label for="visibiliteGroupe">Groupe</label>
							<input id="visibiliteGroupe" name="visibilite" type="radio" required="required" value="groupe" checked/>
							<p>Seul les membres du groupe pourront acceder à ce sondage.</p>
						</div>
					</li>
				<?php endif; ?>
				</ul>
			</td>
		</tr>
	</tbody>
		<tr>
			<th>Secret</th>
			<td>
				<ul>
					<li class="bottomSeparator">
						<div class="visibiliteRadioInput">
							<label for="SecretSecret">Secret</label>
							<input id="id_secret_1" name="secret" type="radio" value="secret" />
							<p>Personne ne sait pour qui les autres membres ont voté.</p>
						</div>
					</li>
					<li class="bottomSeparator">
						<div class="visibiliteRadioInput">
							<label for="SecretScrutin">Secret Scrutin</label>
							<input id="id_secret_2" name="secret" type="radio" value="secret_scrutin" />
							<p>Personne ne sait pour qui les autres membres ont voté avant la fin du scrutin, puis les votes deviennent publics lors des résultats.</p>
						</div>
					</li>	
					<li class="bottomSeparator">
						<div class="visibiliteRadioInput">
							<label for="SecretPublic">Public</label>
							<input id="id_secret_3" name="secret" type="radio" value="public" checked />
							<p>Les choix des membres sont visualisables pour tout les autres membres.</p>
						</div>
					</li>
				</ul>
			</td>
		</tr>
	</tbody>
	

</table>					
<input name="administrateur_id" type="hidden" value="<?php 
echo $_SESSION['id']; 
?>"/>
<?php 
if (isset($_GET['params'])){
  echo '<input name="id_groupe" type="hidden" value="'.$_GET['params'].'"/>';
}
?>
<a href="javascript:addElement();" ><center><h3>Ajouter une option</h3></a><br /> <a href="javascript:removeElement();" ><h3>Supprimer</h3></a></center><br />
<div id="optionsdyn">
 </div>	
<p><input name="submit" type="submit" value="Créer" /></p>
</form>
</div>


<br /><br /><br /><br />
