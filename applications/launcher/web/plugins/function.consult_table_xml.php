<?php
function smarty_function_consult_table_xml($params)
{

  extract($params);
  
  $gateway = Application::getDataGateway("$table_name");
  $MaxConsecutivo = call_user_func(array($gateway,"maxIdVehicleHistory"));
  $v = call_user_func(array($gateway,"getAll$table_name"));
  //print_r("<pre>");
  //print_r($MaxConsecutivo);
  $html='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
         <VFData>';
  $arraykeys = array();

  if (is_array($v))
  {  
    $arraykeys = array_keys($v[0]);
    //print_r($arraykeys);
    for ($i=0;$i<count($v);$i++)
    {
     $html.="<".$itemName.">";
     for ($j=0;$j<count($arraykeys);$j++)
     {
      if($arraykeys[$j]=="consecutivo")
      {
        $html.="  <".$arraykeys[$j].">".$MaxConsecutivo."</".$arraykeys[$j].">";
        $MaxConsecutivo++;

      }else
         $html.="  <".$arraykeys[$j].">".$v[$i][$arraykeys[$j]]."</".$arraykeys[$j].">";
     } 
     $html.="</".$itemName.">";   

    }
  }   
  else
  {
    $html.="<NODATAFOUND>NULL</NODATAFOUND>";

  }   
  $html.="   </VFData>";
  $report_output = Application::getReportDirectory() . "/" . $ruta . "/generados/".$file_name;
  file_put_contents($report_output,$html);
  echo "PROCESO FINALIZADO CON EXITO !!";
}  

?>