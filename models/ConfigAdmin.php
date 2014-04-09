<?php
require_once ROOT . '/models/BD.php';
class ConfigAdmin extends BD
{
    private $inscriptions;
    
    public function __construct()
    {
        switch (func_num_args()) {
            case 0:
                $this->constructeurVide();
                break;

            case 1:
                $this->constructeurPlein(func_get_arg(0));
                break;
        }
    }
    
    public function constructeurVide()
    {
        $sql = "SELECT type, valeur
              FROM config
              WHERE type = 'inscriptions'";
        $lectBdd = $this->executerRequete($sql, array());
        if (($enrBdd = $lectBdd->fetch()) != false) {
            $this->inscriptions = $enrBdd["valeur"];
        }
        return $lectBdd;
    }
    
    public function constructeurPlein()
    {
        $sql = "SELECT type, valeur
              FROM config
              WHERE type = inscriptions";
        $lectBdd = $this->executerRequete($sql, array($idSond));
        if (($enrBdd = $lectBdd->fetch()) != false) {
            $this->inscriptions = $enrBdd["valeur"];
        }
        return $lectBdd;
    }
    
    public function getInscriptions()
    {
        return $this->inscriptions;
    }
    
    public function setInscriptions($s)
    {
        $this->inscriptions = $s;
    }
    
    /* Modifie la config en base
    @return: vrai si la config a été modifiée, faux sinon
    */
    public function update()
    {
        $sql = "UPDATE config SET
			valeur = ?
			WHERE type = 'inscriptions'";
        $update = $this->executerRequete($sql, array($this->inscriptions));
        return ($update->rowCount() == 1);
    }
}
?>