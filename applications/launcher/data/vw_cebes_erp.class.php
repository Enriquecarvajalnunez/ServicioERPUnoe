<?php

class vw_cebes_erp
{
    var $connection;
    var $consult;

    function __construct()
    {
        $this->connection = Application::getDatabaseConnection();
        $this->connection->SetFetchMode(2);
    }

    function getAllvw_cebes_erp()
    {
        $sql = "SELECT * FROM vw_cebes_erp";
        $this->consult = $this->connection->GetAll($sql);
        //print_r($this->consult);
        return $this->consult;
    }
}

?>