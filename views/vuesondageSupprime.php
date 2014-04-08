<?php $this->titre = "Sondage Supprimé !"; 

if($retour == 1){
	echo "<b>Le sondage a bien été supprimé !</b>";
}
elseif($retour == 0){
	echo "<b>Vous n'êtes pas administrateur de ce sondage, vous ne pouvez pas le supprimer !</b>";
}
else{
	echo "<b>Erreur lors de la suppresion de ce sondage. Merci de réessayer plus tard.<br /> Si l'erreur persiste, contacter l'administrateur du site.</b>";
}
?>