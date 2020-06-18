<?php

/*
*Clase de la data Gateway que permite  recuperar los datos a la base de datos
*@Autor: Enrique Nuñez
*Mayo 05 del 2020
*/
class vw_cebes_erp
{
    var $connection;
    var $connection2;
    var $consult;
    var $consult2;

    function __construct()
    {
        $this->connection = Application::getDatabaseConnection();
        $this->connection2 = Application::getDatabaseConnection("bd2");
        
        //Permite generar arreglo asociativo
        $this->connection->SetFetchMode(2);
        $this->connection2->SetFetchMode(2);
    }

    function getAllvw_cebes_erp()
    {        
        $sql2 = "select    TRIM(BOTH FROM ORDERNUMBER) as pedido,
                           to_char(to_date(ORDERDATE,'YYYY/MM/DD'),'YYYY-MM-DD')as fechapedido,
                           MOTORNUMBER as motor,
                           CHASSIS as chasis, 
                           COLOR as  color,  
                           MODELUUID as modelo,
                        to_char(to_date(ENSAMBLEDATE,'YYYY/MM/DD'),'YYYY-MM-DD')as fechaensamble,
                           IMPORTDECLARATION as declaimportacion,
                           BANKDATE as fechabanco,
                           NATIONALNUMBER as nrolevante,
                           '0C' AS consecutivo,
                           SEATS as puestos,
                           SERVICETYPE as servicio,
                           PAINTCOLOR as colorpintura,
                           PAINTCOLOR AS colorcalcomania,
                           ANOMODELO as fechamodelo
                            FROM vm_impacta_motos_xml
                            where ensambledate >= TRUNC(sysdate-3)
                            ";     
                            
                            //rownum <= 100            

        //$this->consult = $this->connection->GetAll($sql);                        
        $this->consult2 = $this->connection2->GetAll($sql2);
        //$result = array_merge($this->consult,$this->consult2); 
        $this->consult2 = $this->change_case_recursive($this->consult2);
        //print_r($this->consult);
        return $this->consult2;
    }

    /*change_case_recursive cambia a minuscula*/
    function change_case_recursive($arr){
     foreach ($arr as $key=>$val){
        if (!is_array($arr[$key])){
            $arr[$key]=($arr[$key]);
            //$arr[$key]=mb_strtoupper($arr[$key]); Antes todo lo pasaba con Mayusculas
            //print_r("<br>".$arr[$key]);
        }else{
            $arr[$key]=array_change_key_case($arr[$key],CASE_LOWER);
            $arr[$key]=$this->change_case_recursive($arr[$key]);
        }
     }
     return $arr;   
    }

    /*Esta función estrae el ultimo consecutivo de la BD de impacta

    */
    function maxIdVehicleHistory()
    {
      $sql = "SELECT max(TO_NUMBER(t.consecutive)) as id FROM im_vehiclehistory t";

      $this->consult = $this->connection->Execute($sql);
        $max=$this->consult->fields;
        return $max['ID']+1;
    }

}//Fin clase

?>