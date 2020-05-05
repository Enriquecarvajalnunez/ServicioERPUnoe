<?php
function smarty_function_consult_table_xml($params)
{

  extract($params);
  
  $gateway = Application::getDataGateway("$table_name");
   
  $v = call_user_func(array($gateway,"getAll$table_name"));
  print_r($v);
  $html='<?xml version="1.0"?>
         <root>';
  $arraykeys = array();

  if (is_array($v))
  {  
    $arraykeys = array_keys($v[0]);
    //print_r($arraykeys);
    for ($i=0;$i<count($v);$i++)
    {
     $html.="<".strtoupper($table_name).">";
     for ($j=0;$j<count($arraykeys);$j++)
     {
         $html.="  <".$arraykeys[$j].">".$v[$i][$arraykeys[$j]]."</".$arraykeys[$j].">";

     } 
     $html.="</".strtoupper($table_name).">";   

    }
  }   
  else
  {
    $html.="<NODATAFOUND>NULL</NODATAFOUND>";

  }   
  $html.="   </root>";
  $report_output = Application::getReportDirectory() . "/" . $ruta . "/generados/".$file_name;
  file_put_contents($report_output,$html);
  echo $html;
}  

?>