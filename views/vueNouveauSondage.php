<?php 

$this->titre = "Nouveau Sondage";

?> 

<div>

<form method="POST" action="http://localhost/sondage-L3/controllers/ControllerSondage.php?action=nouveauSondage" id="formulaire_nouv_sond" >
<p><label>Titre du sondage :   </label><input id="id_titre" name="titre" type="text" required="required" /></p>
<p><label>Description :   </label><input id="id_description" name="description" type="text" required="required" /></p>
<p><label>Visibilité :    </label><br />
    <input id="id_visiblite_1" name="visiblite" type="radio" required="required" value="public" /> Public<br />
    <input id="id_visiblite_2" name="visiblite" type="radio" required="required" value="inscrits" /> Inscrits<br />
    <input id="id_visiblite_3" name="visiblite" type="radio" required="required" value="groupe" /> Groupe<br />
    <input id="id_visiblite_4" name="visiblite" type="radio" required="required" value="prive" /> Privé<br /></p>
<p><label>Date de fin :    </label><input name="date_fin" type="date" /></p>
<p><label>Secret :   </label><br />
    <input id="id_secret_1" name="secret" type="radio" value="secret" /> <label for="id_secret_1">secret</label><br />
    <input id="id_secret_2" name="secret" type="radio" value="secret_scrutin" /> <label for="id_secret_2">secret_scrutin</label><br />
    <input id="id_secret_3" name="secret" type="radio" value="public" /> <label for="id_secret_3">public</label><br /></p>    
<input name="administrateur_id" type="hidden" value="<?php 
echo $_SESSION['id']; 
?>"/>
<?php 
if (isset($_GET['params'])){
  echo '<input name="id_groupe" type="hidden" value="'.$_GET['params'].'"/>';
}
?>

<p><input name="submit" type="submit" /></p>
</form>  
</div>
